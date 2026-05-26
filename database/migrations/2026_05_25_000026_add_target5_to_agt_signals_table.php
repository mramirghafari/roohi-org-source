<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('agt_signals', function (Blueprint $table) {
            if (!Schema::hasColumn('agt_signals', 'target5')) {
                $table->string('target5')->nullable()->after('target4');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agt_signals', function (Blueprint $table) {
            if (Schema::hasColumn('agt_signals', 'target5')) {
                $table->dropColumn('target5');
            }
        });
    }
};