<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // کد معرف یکتا
            $table->string('ref_code', 32)->unique()->nullable()->after('id');

            // معرف/والد مستقیم
            $table->foreignId('referrer_id')
                ->nullable()
                ->after('ref_code')
                ->constrained('users')
                ->nullOnDelete();

            $table->index('referrer_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('referrer_id');
            $table->dropColumn('ref_code');
        });
    }
};
