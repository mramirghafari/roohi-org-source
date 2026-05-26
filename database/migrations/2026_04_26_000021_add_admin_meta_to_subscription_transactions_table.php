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
            if (!Schema::hasColumn('subscription_transactions', 'admin_user_id')) {
                $table->foreignId('admin_user_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('subscription_transactions', 'activated_at')) {
                $table->timestamp('activated_at')->nullable()->after('message');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('subscription_transactions')) {
            return;
        }

        Schema::table('subscription_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_transactions', 'admin_user_id')) {
                $table->dropConstrainedForeignId('admin_user_id');
            }

            if (Schema::hasColumn('subscription_transactions', 'activated_at')) {
                $table->dropColumn('activated_at');
            }
        });
    }
};
