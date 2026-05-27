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
            if (!Schema::hasColumn('payment_campaigns', 'display_capacity')) {
                $table->string('display_capacity', 80)->nullable()->after('capacity');
            }
        });

        if (Schema::hasColumn('payment_campaigns', 'display_capacity')) {
            DB::table('payment_campaigns')
                ->whereNull('display_capacity')
                ->where('capacity', '>', 0)
                ->update(['display_capacity' => DB::raw("CONCAT(capacity, ' نفر')")]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('payment_campaigns') || !Schema::hasColumn('payment_campaigns', 'display_capacity')) {
            return;
        }

        Schema::table('payment_campaigns', function (Blueprint $table) {
            $table->dropColumn('display_capacity');
        });
    }
};