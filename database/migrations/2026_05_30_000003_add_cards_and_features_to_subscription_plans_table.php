<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('subscription_plans')) {
            return;
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_plans', 'card_accounts')) {
                $table->json('card_accounts')->nullable()->after('card_owner');
            }

            if (!Schema::hasColumn('subscription_plans', 'features')) {
                $table->json('features')->nullable()->after('description');
            }
        });

        DB::table('subscription_plans')
            ->whereNotNull('card_number')
            ->orderBy('id')
            ->get()
            ->each(function ($plan) {
                if (!empty($plan->card_accounts)) {
                    return;
                }

                DB::table('subscription_plans')
                    ->where('id', $plan->id)
                    ->update([
                        'card_accounts' => json_encode([
                            [
                                'card_number' => $plan->card_number,
                                'bank_name' => null,
                                'card_owner' => $plan->card_owner,
                            ],
                        ], JSON_UNESCAPED_UNICODE),
                    ]);
            });
    }

    public function down(): void
    {
        if (!Schema::hasTable('subscription_plans')) {
            return;
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            foreach (['card_accounts', 'features'] as $column) {
                if (Schema::hasColumn('subscription_plans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};