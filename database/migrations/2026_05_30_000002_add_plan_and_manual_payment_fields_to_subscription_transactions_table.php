<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('subscription_transactions')) {
            return;
        }

        Schema::table('subscription_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_transactions', 'subscription_plan_id')) {
                $table->foreignId('subscription_plan_id')
                    ->nullable()
                    ->after('admin_user_id')
                    ->constrained('subscription_plans')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('subscription_transactions', 'payment_method')) {
                $table->string('payment_method', 30)->default('gateway')->after('currency');
            }

            if (!Schema::hasColumn('subscription_transactions', 'receipt_path')) {
                $table->string('receipt_path')->nullable()->after('card_pan');
            }

            if (!Schema::hasColumn('subscription_transactions', 'payer_card_number')) {
                $table->string('payer_card_number', 32)->nullable()->after('receipt_path');
            }

            if (!Schema::hasColumn('subscription_transactions', 'manual_paid_at')) {
                $table->timestamp('manual_paid_at')->nullable()->after('payer_card_number');
            }

            if (!Schema::hasColumn('subscription_transactions', 'admin_reviewed_at')) {
                $table->timestamp('admin_reviewed_at')->nullable()->after('activated_at');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('subscription_transactions')) {
            return;
        }

        Schema::table('subscription_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_transactions', 'subscription_plan_id')) {
                $table->dropConstrainedForeignId('subscription_plan_id');
            }

            foreach ([
                'payment_method',
                'receipt_path',
                'payer_card_number',
                'manual_paid_at',
                'admin_reviewed_at',
            ] as $column) {
                if (Schema::hasColumn('subscription_transactions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};