<?php

// database/migrations/2026_01_23_XXXXXX_create_remote_access_tokens_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('remote_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('service', 32)->default('lbank'); // lbank | gmail | ...
            $table->string('token', 64)->unique();           // توکن خام یا هش
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->ipAddress('used_ip')->nullable();
            $table->text('used_ua')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'service', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remote_access_tokens');
    }
};
