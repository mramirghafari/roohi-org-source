<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->text('description')->nullable();
            $table->json('permissions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'created_at']);
        });

        Schema::create('user_role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_role_id')->constrained('user_roles')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            $table->unique(['user_role_id', 'user_id']);
            $table->index(['user_id', 'created_at']);
        });

        $now = now();
        $roles = [
            ['name' => 'مدیر فروش', 'slug' => 'sales-manager', 'permissions' => ['sales.manage', 'users.view_assigned', 'user_groups.assign_marketers']],
            ['name' => 'کارشناس فروش', 'slug' => 'sales-expert', 'permissions' => ['sales.follow_up', 'users.view_assigned']],
            ['name' => 'سرپرست', 'slug' => 'supervisor', 'permissions' => ['team.supervise', 'users.view_assigned']],
            ['name' => 'بازاریاب', 'slug' => 'marketer', 'permissions' => ['marketing.follow_up', 'users.view_assigned']],
            ['name' => 'مدیر پشتیبانی', 'slug' => 'support-manager', 'permissions' => ['tickets.manage', 'support.assign']],
        ];

        foreach ($roles as $role) {
            DB::table('user_roles')->insert([
                'name' => $role['name'],
                'slug' => $role['slug'] ?: Str::slug($role['name']),
                'description' => null,
                'permissions' => json_encode($role['permissions'], JSON_UNESCAPED_UNICODE),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_role_user');
        Schema::dropIfExists('user_roles');
    }
};