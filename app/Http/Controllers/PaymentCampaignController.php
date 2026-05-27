<?php

namespace App\Http\Controllers;

use App\Models\PaymentCampaign;
use App\Models\PaymentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentCampaignController extends Controller
{
    public function index(): View
    {
        $this->importLegacyCampaignsIfPresent();

        $query = PaymentCampaign::query()->latest();

        if ($this->paymentRegistrationsCanBeLinked()) {
            $query->withCount([
                'registrations',
                'registrations as registrations_success_count' => fn ($query) => $query->where('status', PaymentRegistration::STATUS_SUCCESS),
                'registrations as registrations_pending_count' => fn ($query) => $query->where('status', PaymentRegistration::STATUS_PENDING),
            ]);
        }

        $campaigns = $query->get();

        if (!$this->paymentRegistrationsCanBeLinked()) {
            $campaigns->each(function (PaymentCampaign $campaign) {
                $campaign->registrations_count = 0;
                $campaign->registrations_success_count = 0;
                $campaign->registrations_pending_count = 0;
            });
        }

        return view('payment_campaigns.index', compact('campaigns'));
    }

    public function show(PaymentCampaign $paymentCampaign): View
    {
        $campaign = $this->paymentRegistrationsCanBeLinked()
            ? $paymentCampaign->load(['registrations' => fn ($query) => $query->latest()])
            : $paymentCampaign->setRelation('registrations', collect());

        $stats = [
            'total' => $campaign->registrations->count(),
            'success' => $campaign->registrations->where('status', PaymentRegistration::STATUS_SUCCESS)->count(),
            'pending' => $campaign->registrations->where('status', PaymentRegistration::STATUS_PENDING)->count(),
            'failed' => $campaign->registrations
                ->whereIn('status', [
                    PaymentRegistration::STATUS_CANCEL,
                    PaymentRegistration::STATUS_ERROR,
                    PaymentRegistration::STATUS_EXPIRED,
                ])
                ->count(),
        ];

        return view('payment_campaigns.show', compact('campaign', 'stats'));
    }

    public function exportRegistrations(PaymentCampaign $paymentCampaign)
    {
        $registrations = $paymentCampaign->registrations()->latest()->get();
        $fileName = 'payment-campaign-' . $paymentCampaign->id . '-registrations-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'ردیف',
            'عنوان کمپین',
            'نام و نام خانوادگی',
            'موبایل',
            'مبلغ',
            'واحد پول',
            'وضعیت پرداخت',
            'کد وضعیت',
            'کد پیگیری',
            'Authority',
            'Ref ID',
            'شماره کارت',
            'وضعیت درگاه',
            'کد درگاه',
            'پیام',
            'زمان پرداخت',
            'زمان انقضا',
            'زمان ثبت',
            'آخرین بروزرسانی',
        ];

        return response()->streamDownload(function () use ($paymentCampaign, $registrations, $headers) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, $headers, ',', '"', '\\');

            foreach ($registrations as $index => $registration) {
                fputcsv($output, [
                    $index + 1,
                    $paymentCampaign->title,
                    $registration->full_name,
                    $registration->mobile,
                    (int) $registration->amount,
                    $registration->currency,
                    $this->paymentRegistrationStatusLabel((string) $registration->status),
                    $registration->status,
                    $registration->tracking_code,
                    $registration->authority ?: '-',
                    $registration->ref_id ?: '-',
                    $registration->card_pan ?: '-',
                    $registration->gateway_status ?: '-',
                    $registration->gateway_code ?? '-',
                    $registration->message ?: '-',
                    $registration->paid_at ? $registration->paid_at->format('Y-m-d H:i:s') : '-',
                    $registration->expires_at ? $registration->expires_at->format('Y-m-d H:i:s') : '-',
                    $registration->created_at ? $registration->created_at->format('Y-m-d H:i:s') : '-',
                    $registration->updated_at ? $registration->updated_at->format('Y-m-d H:i:s') : '-',
                ], ',', '"', '\\');
            }

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function create(): View
    {
        return view('payment_campaigns.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'original_price' => ['required', 'integer', 'min:0'],
            'current_price' => ['required', 'integer', 'min:1000'],
            'capacity' => ['required', 'integer', 'min:0'],
            'display_capacity' => ['nullable', 'string', 'max:80'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'success_message' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'عنوان کمپین الزامی است.',
            'current_price.required' => 'قیمت فعلی الزامی است.',
            'capacity.required' => 'ظرفیت واقعی کمپین الزامی است.',
        ]);

        if (Schema::hasColumn('payment_campaigns', 'amount')) {
            $data['amount'] = (int) $data['current_price'];
        }
        if (Schema::hasColumn('payment_campaigns', 'currency')) {
            $data['currency'] = (string) config('zarinpal.currency', 'IRT');
        }
        if (Schema::hasColumn('payment_campaigns', 'created_by')) {
            $data['created_by'] = auth()->id();
        }
        if (Schema::hasColumn('payment_campaigns', 'slug')) {
            $data['slug'] = $this->uniqueSlug($data['title']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['button_text'] = $data['button_text'] ?: 'پرداخت و ثبت نام';
        $data['success_message'] = $data['success_message'] ?: 'ثبت نام شما با موفقیت انجام شد.';

        $data = collect($data)
            ->only(Schema::getColumnListing('payment_campaigns'))
            ->all();

        PaymentCampaign::query()->create($data);

        return redirect()->route('payment-campaigns.index')->with('success', 'کمپین جدید با موفقیت ثبت شد.');
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'campaign';
        $slug = $base;
        $counter = 2;

        while (PaymentCampaign::query()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    private function paymentRegistrationStatusLabel(string $status): string
    {
        return match ($status) {
            PaymentRegistration::STATUS_SUCCESS => 'موفق',
            PaymentRegistration::STATUS_PENDING => 'در انتظار',
            PaymentRegistration::STATUS_CANCEL => 'لغو شده',
            PaymentRegistration::STATUS_EXPIRED => 'منقضی',
            PaymentRegistration::STATUS_ERROR => 'ناموفق',
            default => $status,
        };
    }

    private function paymentRegistrationsCanBeLinked(): bool
    {
        return Schema::hasTable('payment_registrations')
            && Schema::hasColumn('payment_registrations', 'payment_campaign_id');
    }

    private function importLegacyCampaignsIfPresent(): void
    {
        if (!Schema::hasTable('payment_campaigns')) {
            return;
        }

        $this->ensurePaymentCampaignColumns();
        $this->ensurePaymentRegistrationCampaignColumn();
        $this->ensureDefaultCampaignExists();

        if (Schema::hasTable('campaigns')) {
            DB::table('campaigns')->orderBy('id')->get()->each(function ($legacyCampaign) {
                $paymentCampaignId = DB::table('payment_campaigns')
                    ->where('slug', 'legacy-campaign-' . $legacyCampaign->id)
                    ->value('id');

                if (!$paymentCampaignId) {
                    $paymentCampaignId = DB::table('payment_campaigns')->insertGetId([
                        'title' => $legacyCampaign->title,
                        'slug' => 'legacy-campaign-' . $legacyCampaign->id,
                        'amount' => (int) $legacyCampaign->current_price,
                        'currency' => (string) config('zarinpal.currency', 'IRT'),
                        'description' => $legacyCampaign->description,
                        'original_price' => (int) $legacyCampaign->original_price,
                        'current_price' => (int) $legacyCampaign->current_price,
                        'capacity' => (int) $legacyCampaign->capacity,
                        'display_capacity' => (int) $legacyCampaign->capacity > 0 ? number_format((int) $legacyCampaign->capacity) . ' نفر' : null,
                        'button_text' => 'پرداخت و ثبت نام',
                        'success_message' => 'ثبت نام شما با موفقیت انجام شد.',
                        'is_active' => (bool) $legacyCampaign->is_active,
                        'created_at' => $legacyCampaign->created_at ?? now(),
                        'updated_at' => $legacyCampaign->updated_at ?? now(),
                    ]);
                }

                $this->importLegacyCampaignRegistrations((int) $legacyCampaign->id, (int) $paymentCampaignId, (int) $legacyCampaign->current_price);
            });
        }

        if (Schema::hasTable('payment_registration_v2_s') && Schema::hasTable('payment_registrations')) {
            DB::table('payment_registration_v2_s')
                ->orderBy('id')
                ->get()
                ->each(function ($registration) {
                    $trackingCode = 'LEGACY-PV2-' . $registration->id;

                    if (DB::table('payment_registrations')->where('tracking_code', $trackingCode)->exists()) {
                        return;
                    }

                    $campaign = $this->legacyPaymentCampaignForRegistration((int) $registration->campaign_id);

                    if (!$campaign) {
                        return;
                    }

                    DB::table('payment_registrations')->insert([
                        'payment_campaign_id' => $campaign->id,
                        'full_name' => $registration->name,
                        'mobile' => $registration->mobile,
                        'amount' => (int) ($campaign->amount ?: $campaign->current_price ?: 0),
                        'currency' => (string) ($campaign->currency ?: config('zarinpal.currency', 'IRT')),
                        'tracking_code' => $trackingCode,
                        'status' => PaymentRegistration::STATUS_SUCCESS,
                        'message' => 'منتقل‌شده از ثبت‌نام V2 قدیمی',
                        'paid_at' => $registration->created_at ?? now(),
                        'created_at' => $registration->created_at ?? now(),
                        'updated_at' => $registration->updated_at ?? now(),
                    ]);
                });
        }
    }

    private function legacyPaymentCampaignForRegistration(int $legacyCampaignId): ?object
    {
        $campaign = DB::table('payment_campaigns')
            ->where('slug', 'legacy-campaign-' . $legacyCampaignId)
            ->first();

        if ($campaign) {
            return $campaign;
        }

        $campaign = DB::table('payment_campaigns')->where('id', $legacyCampaignId)->first();

        if ($campaign) {
            return $campaign;
        }

        return DB::table('payment_campaigns')->where('slug', 'default')->first();
    }

    private function ensureDefaultCampaignExists(): void
    {
        if (!Schema::hasTable('payment_campaigns')) {
            return;
        }

        $defaultCampaignId = Schema::hasColumn('payment_campaigns', 'slug')
            ? DB::table('payment_campaigns')->where('slug', 'default')->value('id')
            : null;

        if (!$defaultCampaignId) {
            $data = [
                'title' => 'ثبت نام سیستم اقتصادی اکو روحی',
                'slug' => 'default',
                'amount' => 4900000,
                'currency' => (string) config('zarinpal.currency', 'IRT'),
                'description' => null,
                'original_price' => 30000000,
                'current_price' => 4900000,
                'capacity' => 20,
                'display_capacity' => '20 نفر',
                'button_text' => 'پرداخت و ثبت نام',
                'success_message' => 'ثبت نام شما با موفقیت انجام شد.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $defaultCampaignId = DB::table('payment_campaigns')->insertGetId(
                collect($data)->only(Schema::getColumnListing('payment_campaigns'))->all()
            );
        }

        if ($this->paymentRegistrationsCanBeLinked()) {
            DB::table('payment_registrations')
                ->whereNull('payment_campaign_id')
                ->update(['payment_campaign_id' => $defaultCampaignId]);
        }
    }

    private function importLegacyCampaignRegistrations(int $legacyCampaignId, int $paymentCampaignId, int $amount): void
    {
        if (!Schema::hasTable('campaign_registrations') || !Schema::hasTable('payment_registrations')) {
            return;
        }

        DB::table('campaign_registrations')
            ->where('campaign_id', $legacyCampaignId)
            ->orderBy('id')
            ->get()
            ->each(function ($registration) use ($paymentCampaignId, $amount) {
                $trackingCode = 'LEGACY-CAMP-' . $registration->id;

                if (DB::table('payment_registrations')->where('tracking_code', $trackingCode)->exists()) {
                    return;
                }

                DB::table('payment_registrations')->insert([
                    'payment_campaign_id' => $paymentCampaignId,
                    'full_name' => $registration->name,
                    'mobile' => $registration->mobile,
                    'amount' => $amount,
                    'currency' => (string) config('zarinpal.currency', 'IRT'),
                    'tracking_code' => $trackingCode,
                    'status' => PaymentRegistration::STATUS_SUCCESS,
                    'message' => 'منتقل‌شده از کمپین قدیمی',
                    'paid_at' => $registration->created_at ?? now(),
                    'created_at' => $registration->created_at ?? now(),
                    'updated_at' => $registration->updated_at ?? now(),
                ]);
            });
    }

    private function ensurePaymentCampaignColumns(): void
    {
        Schema::table('payment_campaigns', function ($table) {
            if (!Schema::hasColumn('payment_campaigns', 'slug')) {
                $table->string('slug', 100)->nullable()->unique()->after('title');
            }
            if (!Schema::hasColumn('payment_campaigns', 'amount')) {
                $table->unsignedBigInteger('amount')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('payment_campaigns', 'currency')) {
                $table->string('currency', 8)->default('IRT')->after('amount');
            }
            if (!Schema::hasColumn('payment_campaigns', 'original_price')) {
                $table->unsignedBigInteger('original_price')->nullable()->after('description');
            }
            if (!Schema::hasColumn('payment_campaigns', 'current_price')) {
                $table->unsignedBigInteger('current_price')->nullable()->after('original_price');
            }
            if (!Schema::hasColumn('payment_campaigns', 'capacity')) {
                $table->unsignedInteger('capacity')->default(0)->after('current_price');
            }
            if (!Schema::hasColumn('payment_campaigns', 'display_capacity')) {
                $table->string('display_capacity', 80)->nullable()->after('capacity');
            }
            if (!Schema::hasColumn('payment_campaigns', 'button_text')) {
                $table->string('button_text', 80)->nullable()->after('display_capacity');
            }
            if (!Schema::hasColumn('payment_campaigns', 'success_message')) {
                $table->string('success_message', 255)->nullable()->after('button_text');
            }
        });
    }

    private function ensurePaymentRegistrationCampaignColumn(): void
    {
        if (!Schema::hasTable('payment_registrations') || Schema::hasColumn('payment_registrations', 'payment_campaign_id')) {
            return;
        }

        Schema::table('payment_registrations', function ($table) {
            $table->foreignId('payment_campaign_id')
                ->nullable()
                ->after('id')
                ->constrained('payment_campaigns')
                ->nullOnDelete();
        });
    }
}
