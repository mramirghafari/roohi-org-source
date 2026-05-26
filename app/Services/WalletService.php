<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WalletService
{
    private const STARS_TO_TOMAN_RATE = 30000;
    private const STARS_TO_USDT_DIVISOR = 5;

    public function ensureWallet(User $user): UserWallet
    {
        return UserWallet::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'toman_balance' => 0,
                'usdt_balance' => 0,
                'stars_balance' => 0,
            ]
        );
    }

    public function deposit(User $user, string $asset, float $amount, ?string $description = null, ?User $creator = null): WalletTransaction
    {
        $asset = $this->normalizeAsset($asset);
        $amount = $this->normalizeAmount($asset, $amount);

        return DB::transaction(function () use ($user, $asset, $amount, $description, $creator) {
            $wallet = $this->lockWallet($user);
            $field = $this->fieldForAsset($asset);

            $before = (float) $this->valueForAsset($wallet, $asset);
            $after = $before + $amount;

            $this->setWalletValue($wallet, $asset, $after);
            $wallet->save();

            return WalletTransaction::query()->create([
                'user_id' => $user->id,
                'created_by' => $creator?->id,
                'asset' => $asset,
                'type' => WalletTransaction::TYPE_DEPOSIT,
                'amount' => $this->formatForTransaction($amount),
                'balance_before' => $this->formatForTransaction($before),
                'balance_after' => $this->formatForTransaction($after),
                'description' => $description,
                'meta' => [
                    'wallet_field' => $field,
                ],
            ]);
        });
    }

    public function withdraw(User $user, string $asset, float $amount, ?string $description = null, ?User $creator = null): WalletTransaction
    {
        $asset = $this->normalizeAsset($asset);
        $amount = $this->normalizeAmount($asset, $amount);

        return DB::transaction(function () use ($user, $asset, $amount, $description, $creator) {
            $wallet = $this->lockWallet($user);

            $before = (float) $this->valueForAsset($wallet, $asset);
            if ($before < $amount) {
                throw ValidationException::withMessages([
                    'amount' => 'موجودی کیف پول کافی نیست.',
                ]);
            }

            $after = $before - $amount;

            $this->setWalletValue($wallet, $asset, $after);
            $wallet->save();

            return WalletTransaction::query()->create([
                'user_id' => $user->id,
                'created_by' => $creator?->id,
                'asset' => $asset,
                'type' => WalletTransaction::TYPE_WITHDRAW,
                'amount' => $this->formatForTransaction($amount),
                'balance_before' => $this->formatForTransaction($before),
                'balance_after' => $this->formatForTransaction($after),
                'description' => $description,
            ]);
        });
    }

    public function transferByMobile(User $fromUser, string $receiverMobile, string $asset, float $amount, ?string $description = null): array
    {
        $asset = $this->normalizeAsset($asset);
        $amount = $this->normalizeAmount($asset, $amount);

        $receiverCandidates = User::query()->where('mobile', $receiverMobile)->get();
        if ($receiverCandidates->count() === 0) {
            throw ValidationException::withMessages([
                'receiver_mobile' => 'کاربری با این شماره موبایل پیدا نشد.',
            ]);
        }

        if ($receiverCandidates->count() > 1) {
            throw ValidationException::withMessages([
                'receiver_mobile' => 'برای این شماره موبایل چند کاربر ثبت شده است. ابتدا داده‌ها را یکسان‌سازی کنید.',
            ]);
        }

        $toUser = $receiverCandidates->first();

        if ((int) $toUser->id === (int) $fromUser->id) {
            throw ValidationException::withMessages([
                'receiver_mobile' => 'انتقال به خود کاربر مجاز نیست.',
            ]);
        }

        return DB::transaction(function () use ($fromUser, $toUser, $asset, $amount, $description) {
            $orderedIds = [$fromUser->id, $toUser->id];
            sort($orderedIds);

            foreach ($orderedIds as $userId) {
                $user = $userId === (int) $fromUser->id ? $fromUser : $toUser;
                $this->ensureWallet($user);
            }

            $wallets = UserWallet::query()
                ->whereIn('user_id', $orderedIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('user_id');

            $fromWallet = $wallets->get($fromUser->id);
            $toWallet = $wallets->get($toUser->id);

            $fromBefore = (float) $this->valueForAsset($fromWallet, $asset);
            if ($fromBefore < $amount) {
                throw ValidationException::withMessages([
                    'amount' => 'موجودی کیف پول کافی نیست.',
                ]);
            }

            $toBefore = (float) $this->valueForAsset($toWallet, $asset);
            $fromAfter = $fromBefore - $amount;
            $toAfter = $toBefore + $amount;

            $this->setWalletValue($fromWallet, $asset, $fromAfter);
            $this->setWalletValue($toWallet, $asset, $toAfter);

            $fromWallet->save();
            $toWallet->save();

            $outTransaction = WalletTransaction::query()->create([
                'user_id' => $fromUser->id,
                'counterparty_user_id' => $toUser->id,
                'created_by' => $fromUser->id,
                'asset' => $asset,
                'type' => WalletTransaction::TYPE_TRANSFER_OUT,
                'amount' => $this->formatForTransaction($amount),
                'balance_before' => $this->formatForTransaction($fromBefore),
                'balance_after' => $this->formatForTransaction($fromAfter),
                'description' => $description,
                'meta' => [
                    'receiver_mobile' => $toUser->mobile,
                ],
            ]);

            $inTransaction = WalletTransaction::query()->create([
                'user_id' => $toUser->id,
                'counterparty_user_id' => $fromUser->id,
                'created_by' => $fromUser->id,
                'asset' => $asset,
                'type' => WalletTransaction::TYPE_TRANSFER_IN,
                'amount' => $this->formatForTransaction($amount),
                'balance_before' => $this->formatForTransaction($toBefore),
                'balance_after' => $this->formatForTransaction($toAfter),
                'description' => $description,
                'meta' => [
                    'sender_mobile' => $fromUser->mobile,
                ],
            ]);

            return [
                'from_user' => $fromUser,
                'to_user' => $toUser,
                'out_transaction' => $outTransaction,
                'in_transaction' => $inTransaction,
            ];
        });
    }

    public function reward(User $user, string $asset, float $amount, string $description, array $meta = [], ?User $creator = null): WalletTransaction
    {
        $asset = $this->normalizeAsset($asset);
        $amount = $this->normalizeAmount($asset, $amount);

        return DB::transaction(function () use ($user, $asset, $amount, $description, $meta, $creator) {
            $wallet = $this->lockWallet($user);

            $before = (float) $this->valueForAsset($wallet, $asset);
            $after = $before + $amount;

            $this->setWalletValue($wallet, $asset, $after);
            $wallet->save();

            return WalletTransaction::query()->create([
                'user_id' => $user->id,
                'created_by' => $creator?->id,
                'asset' => $asset,
                'type' => WalletTransaction::TYPE_REWARD,
                'amount' => $this->formatForTransaction($amount),
                'balance_before' => $this->formatForTransaction($before),
                'balance_after' => $this->formatForTransaction($after),
                'description' => $description,
                'meta' => $meta,
            ]);
        });
    }

    public function swapStars(User $user, int $starsAmount, string $toAsset): array
    {
        $toAsset = $this->normalizeAsset($toAsset);

        if (!in_array($toAsset, [UserWallet::ASSET_TOMAN, UserWallet::ASSET_USDT], true)) {
            throw ValidationException::withMessages([
                'convert_to' => 'مقصد تبدیل فقط می‌تواند تومان یا تتر باشد.',
            ]);
        }

        if ($starsAmount <= 0) {
            throw ValidationException::withMessages([
                'stars_amount' => 'تعداد استارز باید بیشتر از صفر باشد.',
            ]);
        }

        return DB::transaction(function () use ($user, $starsAmount, $toAsset) {
            $wallet = $this->lockWallet($user);

            $starsBefore = (float) $wallet->stars_balance;
            if ($starsBefore < $starsAmount) {
                throw ValidationException::withMessages([
                    'stars_amount' => 'موجودی STARS کافی نیست.',
                ]);
            }

            $targetBefore = $this->valueForAsset($wallet, $toAsset);
            $targetAmount = $toAsset === UserWallet::ASSET_TOMAN
                ? ($starsAmount * self::STARS_TO_TOMAN_RATE)
                : ($starsAmount / self::STARS_TO_USDT_DIVISOR);

            $starsAfter = $starsBefore - $starsAmount;
            $targetAfter = $targetBefore + $targetAmount;

            $wallet->stars_balance = (int) $starsAfter;
            $this->setWalletValue($wallet, $toAsset, $targetAfter);
            $wallet->save();

            $outTransaction = WalletTransaction::query()->create([
                'user_id' => $user->id,
                'created_by' => $user->id,
                'asset' => UserWallet::ASSET_STARS,
                'type' => WalletTransaction::TYPE_SWAP,
                'amount' => $this->formatForTransaction((float) $starsAmount),
                'balance_before' => $this->formatForTransaction($starsBefore),
                'balance_after' => $this->formatForTransaction($starsAfter),
                'description' => 'تبدیل STARS',
                'meta' => [
                    'to_asset' => $toAsset,
                    'to_amount' => $this->formatForTransaction($targetAmount),
                ],
            ]);

            $inTransaction = WalletTransaction::query()->create([
                'user_id' => $user->id,
                'created_by' => $user->id,
                'asset' => $toAsset,
                'type' => WalletTransaction::TYPE_SWAP,
                'amount' => $this->formatForTransaction($targetAmount),
                'balance_before' => $this->formatForTransaction($targetBefore),
                'balance_after' => $this->formatForTransaction($targetAfter),
                'description' => 'دریافت از تبدیل STARS',
                'meta' => [
                    'from_asset' => UserWallet::ASSET_STARS,
                    'from_amount' => $this->formatForTransaction((float) $starsAmount),
                ],
            ]);

            return [
                'stars_spent' => $starsAmount,
                'asset' => $toAsset,
                'asset_amount' => $targetAmount,
                'out_transaction' => $outTransaction,
                'in_transaction' => $inTransaction,
            ];
        });
    }

    private function lockWallet(User $user): UserWallet
    {
        $this->ensureWallet($user);

        return UserWallet::query()
            ->where('user_id', $user->id)
            ->lockForUpdate()
            ->firstOrFail();
    }

    private function normalizeAsset(string $asset): string
    {
        $asset = strtolower(trim($asset));

        if (!in_array($asset, UserWallet::ASSETS, true)) {
            throw ValidationException::withMessages([
                'asset' => 'نوع کیف پول نامعتبر است.',
            ]);
        }

        return $asset;
    }

    private function normalizeAmount(string $asset, float $amount): float
    {
        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'مقدار باید بیشتر از صفر باشد.',
            ]);
        }

        if ($asset === UserWallet::ASSET_STARS) {
            if ((int) $amount != $amount) {
                throw ValidationException::withMessages([
                    'amount' => 'مقدار کیف پول stars باید عدد صحیح باشد.',
                ]);
            }

            return (float) (int) $amount;
        }

        return round($amount, $asset === UserWallet::ASSET_TOMAN ? 2 : 8);
    }

    private function fieldForAsset(string $asset): string
    {
        return match ($asset) {
            UserWallet::ASSET_TOMAN => 'toman_balance',
            UserWallet::ASSET_USDT => 'usdt_balance',
            UserWallet::ASSET_STARS => 'stars_balance',
        };
    }

    private function valueForAsset(UserWallet $wallet, string $asset): float
    {
        return match ($asset) {
            UserWallet::ASSET_TOMAN => (float) $wallet->toman_balance,
            UserWallet::ASSET_USDT => (float) $wallet->usdt_balance,
            UserWallet::ASSET_STARS => (float) $wallet->stars_balance,
        };
    }

    private function setWalletValue(UserWallet $wallet, string $asset, float $value): void
    {
        if ($asset === UserWallet::ASSET_STARS) {
            $wallet->stars_balance = max(0, (int) round($value));
            return;
        }

        if ($asset === UserWallet::ASSET_TOMAN) {
            $wallet->toman_balance = round($value, 2);
            return;
        }

        $wallet->usdt_balance = round($value, 8);
    }

    private function formatForTransaction(float $value): string
    {
        return number_format($value, 8, '.', '');
    }
}
