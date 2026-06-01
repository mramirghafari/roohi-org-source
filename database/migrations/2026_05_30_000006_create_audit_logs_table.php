<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event', 80)->index();
            $table->string('action', 160)->nullable()->index();
            $table->string('area', 80)->nullable()->index();
            $table->string('method', 12)->nullable();
            $table->string('route_name', 160)->nullable()->index();
            $table->string('path', 500)->nullable();
            $table->text('full_url')->nullable();
            $table->string('ip_address', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->unsignedSmallInteger('status_code')->nullable()->index();
            $table->nullableMorphs('auditable');
            $table->json('actor_roles')->nullable();
            $table->json('actor_permissions')->nullable();
            $table->json('changes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('occurred_at')->useCurrent()->index();
            $table->timestamps();

            $table->index(['user_id', 'occurred_at']);
            $table->index(['event', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};