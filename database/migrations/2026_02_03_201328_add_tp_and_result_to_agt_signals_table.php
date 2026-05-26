<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('agt_signals', function (Blueprint $table) {
            $table->tinyInteger('tp_level')
                  ->default(0)
                  ->after('result');

            $table->tinyInteger('final_result')
                  ->default(0)
                  ->after('tp_level');
        });
    }

    public function down(): void
    {
        Schema::table('agt_signals', function (Blueprint $table) {
            $table->dropColumn(['tp_level', 'final_result']);
        });
    }
};
