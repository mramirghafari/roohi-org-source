<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_groups', function (Blueprint $table) {
            $table->string('commission_mode', 20)->default('inherit')->after('is_active');
            $table->index('commission_mode');
        });
    }

    public function down(): void
    {
        Schema::table('user_groups', function (Blueprint $table) {
            $table->dropIndex(['commission_mode']);
            $table->dropColumn('commission_mode');
        });
    }
};
