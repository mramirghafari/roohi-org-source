<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payment_campaigns')) {
            Schema::create('payment_campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('title', 150);
                $table->string('slug', 100)->nullable()->unique();
                $table->unsignedBigInteger('amount')->nullable();
                $table->string('currency', 8)->default('IRT');
                $table->text('description')->nullable();
                $table->unsignedBigInteger('original_price')->nullable();
                $table->unsignedBigInteger('current_price')->nullable();
                $table->unsignedInteger('capacity')->default(0);
                $table->string('button_text', 80)->nullable();
                $table->string('success_message', 255)->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['is_active', 'created_at']);
            });
        } else {
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
                if (!Schema::hasColumn('payment_campaigns', 'button_text')) {
                    $table->string('button_text', 80)->nullable()->after('capacity');
                }
                if (!Schema::hasColumn('payment_campaigns', 'success_message')) {
                    $table->string('success_message', 255)->nullable()->after('button_text');
                }
                if (!Schema::hasColumn('payment_campaigns', 'created_by')) {
                    $table->foreignId('created_by')->nullable()->after('is_active')->constrained('users')->nullOnDelete();
                }
            });
        }

        if (Schema::hasColumn('payment_campaigns', 'slug')) {
            DB::table('payment_campaigns')
                ->whereNull('slug')
                ->orderBy('id')
                ->get(['id'])
                ->each(function ($campaign) {
                    DB::table('payment_campaigns')
                        ->where('id', $campaign->id)
                        ->update(['slug' => 'campaign-' . $campaign->id]);
                });
        }

        if (Schema::hasColumn('payment_campaigns', 'amount')) {
            DB::table('payment_campaigns')
                ->whereNull('amount')
                ->update([
                    'amount' => Schema::hasColumn('payment_campaigns', 'current_price')
                        ? DB::raw('current_price')
                        : 4900000,
                ]);
        }

        $defaultCampaignId = DB::table('payment_campaigns')->where('slug', 'default')->value('id');

        if (!$defaultCampaignId) {
            $defaultCampaignId = DB::table('payment_campaigns')->insertGetId([
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
            ]);
        }

        if (Schema::hasTable('payment_registrations') && !Schema::hasColumn('payment_registrations', 'payment_campaign_id')) {
            Schema::table('payment_registrations', function (Blueprint $table) {
                $table->foreignId('payment_campaign_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('payment_campaigns')
                    ->nullOnDelete();
            });

            DB::table('payment_registrations')->update([
                'payment_campaign_id' => $defaultCampaignId,
            ]);

            Schema::table('payment_registrations', function (Blueprint $table) {
                $table->index(['payment_campaign_id', 'status', 'created_at'], 'payment_reg_campaign_status_created_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payment_registrations') && Schema::hasColumn('payment_registrations', 'payment_campaign_id')) {
            Schema::table('payment_registrations', function (Blueprint $table) {
                $table->dropIndex('payment_reg_campaign_status_created_index');
                $table->dropConstrainedForeignId('payment_campaign_id');
            });
        }

        Schema::dropIfExists('payment_campaigns');
    }
};
