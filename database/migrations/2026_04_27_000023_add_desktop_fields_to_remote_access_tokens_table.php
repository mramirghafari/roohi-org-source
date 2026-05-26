<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('remote_access_tokens', function (Blueprint $table) {
            if (!Schema::hasColumn('remote_access_tokens', 'username')) {
                $table->string('username', 120)->nullable()->after('token');
            }
            if (!Schema::hasColumn('remote_access_tokens', 'password')) {
                $table->text('password')->nullable()->after('username');
            }
            if (!Schema::hasColumn('remote_access_tokens', 'is_enabled')) {
                $table->boolean('is_enabled')->default(true)->after('password');
            }
            if (!Schema::hasColumn('remote_access_tokens', 'meta')) {
                $table->json('meta')->nullable()->after('is_enabled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('remote_access_tokens', function (Blueprint $table) {
            foreach (['meta', 'is_enabled', 'password', 'username'] as $column) {
                if (Schema::hasColumn('remote_access_tokens', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
