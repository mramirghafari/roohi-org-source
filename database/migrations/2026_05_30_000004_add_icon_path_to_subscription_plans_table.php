<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('subscription_plans')) {
            return;
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_plans', 'icon_path')) {
                $table->string('icon_path')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('subscription_plans')) {
            return;
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_plans', 'icon_path')) {
                $table->dropColumn('icon_path');
            }
        });
    }
};