<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('user_group_support_accounts', 'assignment_role')) {
            Schema::table('user_group_support_accounts', function (Blueprint $table) {
                $table->string('assignment_role', 40)->default('sales_support')->after('assigned_by');
            });
        }

        Schema::table('user_group_support_accounts', function (Blueprint $table) {
            if (!$this->indexExists('user_group_support_accounts', 'user_group_support_group_id_index')) {
                $table->index('user_group_id', 'user_group_support_group_id_index');
            }
        });

        Schema::table('user_group_support_accounts', function (Blueprint $table) {
            if ($this->indexExists('user_group_support_accounts', 'user_group_support_accounts_user_group_id_support_user_id_unique')) {
                $table->dropUnique('user_group_support_accounts_user_group_id_support_user_id_unique');
            }

            if (!$this->indexExists('user_group_support_accounts', 'user_group_support_role_user_unique')) {
                $table->unique(['user_group_id', 'assignment_role', 'support_user_id'], 'user_group_support_role_user_unique');
            }

            if (!$this->indexExists('user_group_support_accounts', 'user_group_support_role_user_index')) {
                $table->index(['assignment_role', 'support_user_id'], 'user_group_support_role_user_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_group_support_accounts', function (Blueprint $table) {
            if ($this->indexExists('user_group_support_accounts', 'user_group_support_role_user_unique')) {
                $table->dropUnique('user_group_support_role_user_unique');
            }

            if ($this->indexExists('user_group_support_accounts', 'user_group_support_role_user_index')) {
                $table->dropIndex('user_group_support_role_user_index');
            }

            if (!$this->indexExists('user_group_support_accounts', 'user_group_support_accounts_user_group_id_support_user_id_unique')) {
                $table->unique(['user_group_id', 'support_user_id'], 'user_group_support_accounts_user_group_id_support_user_id_unique');
            }

            if ($this->indexExists('user_group_support_accounts', 'user_group_support_group_id_index')) {
                $table->dropIndex('user_group_support_group_id_index');
            }

            if (Schema::hasColumn('user_group_support_accounts', 'assignment_role')) {
                $table->dropColumn('assignment_role');
            }
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        return !empty(DB::select('SHOW INDEX FROM `' . $table . '` WHERE Key_name = ?', [$index]));
    }
};