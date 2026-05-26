<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('notifs')) {
            return;
        }

        if (Schema::hasColumn('notifs', 'archive_notif_id')) {
            return;
        }

        Schema::table('notifs', function (Blueprint $table) {
            $table->unsignedBigInteger('archive_notif_id')
                ->nullable()
                ->after('user_id');

            $table->foreign('archive_notif_id')
                ->references('id')
                ->on('archive_notifs')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('notifs') || !Schema::hasColumn('notifs', 'archive_notif_id')) {
            return;
        }

        Schema::table('notifs', function (Blueprint $table) {
            $table->dropForeign(['archive_notif_id']);
            $table->dropColumn('archive_notif_id');
        });
    }
};
