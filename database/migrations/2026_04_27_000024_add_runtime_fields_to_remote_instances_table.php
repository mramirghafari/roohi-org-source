<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('remote_instances', function (Blueprint $table) {
            if (!Schema::hasColumn('remote_instances', 'external_port')) {
                $table->unsignedInteger('external_port')->nullable()->after('internal_port');
            }
            if (!Schema::hasColumn('remote_instances', 'access_url')) {
                $table->string('access_url', 500)->nullable()->after('external_port');
            }
            if (!Schema::hasColumn('remote_instances', 'status')) {
                $table->string('status', 32)->default('running')->after('access_url');
            }
            if (!Schema::hasColumn('remote_instances', 'last_seen_at')) {
                $table->timestamp('last_seen_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('remote_instances', function (Blueprint $table) {
            foreach (['last_seen_at', 'status', 'access_url', 'external_port'] as $column) {
                if (Schema::hasColumn('remote_instances', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
