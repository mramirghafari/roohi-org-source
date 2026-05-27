<?php

use App\Models\PaymentRegistration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->ensurePaymentCampaignColumns();
        $this->ensurePaymentRegistrationCampaignColumn();
        $defaultCampaignId = $this->ensureDefaultCampaignExists();

        if ($defaultCampaignId && Schema::hasColumn('payment_registrations', 'payment_campaign_id')) {
            DB::table('payment_registrations')
                ->whereNull('payment_campaign_id')
                ->update(['payment_campaign_id' => $defaultCampaignId]);
        }

        if (Schema::hasTable('campaigns')) {
            $this->moveLegacyCampaigns();
        }

        if (Schema::hasTable('payment_registration_v2_s')) {
            $this->movePaymentRegistrationV2Rows();
        }

        Schema::dropIfExists('payment_registration_v2_s');
        Schema::dropIfExists('campaign_registrations');
        Schema::dropIfExists('campaigns');
    }

    public function down(): void
    {
        if (!Schema::hasTable('campaigns')) {
            Schema::create('campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->unsignedBigInteger('original_price');
                $table->unsignedBigInteger('current_price');
                $table->unsignedInteger('capacity')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('campaign_registrations')) {
            Schema::create('campaign_registrations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('campaign_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('name');
                $table->string('mobile');
                $table->string('email')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('payment_registration_v2_s')) {
            Schema::create('payment_registration_v2_s', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('campaign_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('name');
                $table->string('mobile');
                $table->string('email')->nullable();
                $table->timestamps();
            });
        }
    }

    private function moveLegacyCampaigns(): void
    {
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
                    'button_text' => 'پرداخت و ثبت نام',
                    'success_message' => 'ثبت نام شما با موفقیت انجام شد.',
                    'is_active' => (bool) $legacyCampaign->is_active,
                    'created_at' => $legacyCampaign->created_at ?? now(),
                    'updated_at' => $legacyCampaign->updated_at ?? now(),
                ]);
            }

            if (!Schema::hasTable('campaign_registrations')) {
                return;
            }

            DB::table('campaign_registrations')
                ->where('campaign_id', $legacyCampaign->id)
                ->orderBy('id')
                ->get()
                ->each(function ($registration) use ($paymentCampaignId, $legacyCampaign) {
                    $trackingCode = 'LEGACY-CAMP-' . $registration->id;

                    if (DB::table('payment_registrations')->where('tracking_code', $trackingCode)->exists()) {
                        return;
                    }

                    DB::table('payment_registrations')->insert([
                        'payment_campaign_id' => $paymentCampaignId,
                        'full_name' => $registration->name,
                        'mobile' => $registration->mobile,
                        'amount' => (int) $legacyCampaign->current_price,
                        'currency' => (string) config('zarinpal.currency', 'IRT'),
                        'tracking_code' => $trackingCode,
                        'status' => PaymentRegistration::STATUS_SUCCESS,
                        'message' => 'منتقل‌شده از کمپین قدیمی',
                        'paid_at' => $registration->created_at ?? now(),
                        'created_at' => $registration->created_at ?? now(),
                        'updated_at' => $registration->updated_at ?? now(),
                    ]);
                });
        });
    }

    private function ensureDefaultCampaignExists(): ?int
    {
        if (!Schema::hasTable('payment_campaigns')) {
            return null;
        }

        $defaultCampaignId = Schema::hasColumn('payment_campaigns', 'slug')
            ? DB::table('payment_campaigns')->where('slug', 'default')->value('id')
            : null;

        if ($defaultCampaignId) {
            return (int) $defaultCampaignId;
        }

        $data = [
            'title' => 'ثبت نام سیستم اقتصادی اکو روحی',
            'slug' => 'default',
            'amount' => 4900000,
            'currency' => (string) config('zarinpal.currency', 'IRT'),
            'description' => null,
            'original_price' => 30000000,
            'current_price' => 4900000,
            'capacity' => 20,
            'button_text' => 'پرداخت و ثبت نام',
            'success_message' => 'ثبت نام شما با موفقیت انجام شد.',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return (int) DB::table('payment_campaigns')->insertGetId(
            collect($data)->only(Schema::getColumnListing('payment_campaigns'))->all()
        );
    }

    private function movePaymentRegistrationV2Rows(): void
    {
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

    private function ensurePaymentCampaignColumns(): void
    {
        if (!Schema::hasTable('payment_campaigns')) {
            return;
        }

        Schema::table('payment_campaigns', function (Blueprint $table) {
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
            if (!Schema::hasColumn('payment_campaigns', 'button_text')) {
                $table->string('button_text', 80)->nullable()->after('capacity');
            }
            if (!Schema::hasColumn('payment_campaigns', 'success_message')) {
                $table->string('success_message', 255)->nullable()->after('button_text');
            }
        });
    }

    private function ensurePaymentRegistrationCampaignColumn(): void
    {
        if (!Schema::hasTable('payment_registrations')) {
            return;
        }

        if (!Schema::hasColumn('payment_registrations', 'payment_campaign_id')) {
            Schema::table('payment_registrations', function (Blueprint $table) {
                $table->foreignId('payment_campaign_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('payment_campaigns')
                    ->nullOnDelete();
            });
        }
    }
};
