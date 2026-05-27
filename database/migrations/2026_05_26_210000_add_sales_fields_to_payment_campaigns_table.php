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
            return;
        }

        Schema::table('payment_campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_campaigns', 'original_price')) {
                $table->unsignedBigInteger('original_price')->nullable()->after('description');
            }
            if (!Schema::hasColumn('payment_campaigns', 'current_price')) {
                $table->unsignedBigInteger('current_price')->nullable()->after('original_price');
            }
            if (!Schema::hasColumn('payment_campaigns', 'capacity')) {
                $table->unsignedInteger('capacity')->default(0)->after('current_price');
            }
        });

        DB::table('payment_campaigns')
            ->whereNull('current_price')
            ->update(['current_price' => DB::raw('amount')]);

        DB::table('payment_campaigns')
            ->whereNull('original_price')
            ->update(['original_price' => DB::raw('amount')]);

        DB::table('payment_campaigns')
            ->where('slug', 'default')
            ->update([
                'original_price' => 30000000,
                'current_price' => 4900000,
                'capacity' => 20,
            ]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('payment_campaigns')) {
            return;
        }

        Schema::table('payment_campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('payment_campaigns', 'capacity')) {
                $table->dropColumn('capacity');
            }
            if (Schema::hasColumn('payment_campaigns', 'current_price')) {
                $table->dropColumn('current_price');
            }
            if (Schema::hasColumn('payment_campaigns', 'original_price')) {
                $table->dropColumn('original_price');
            }
        });
    }
};
