<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('remote_entry_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('token', 64)->unique();
            $table->string('scope', 32)->default('lbank'); // lbank, gmail, ...

            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();

            // سشن کوتاه‌عمر بعد از مصرف توکن (برای noVNC assets + websocket)
            $table->string('session_key', 80)->nullable()->unique();
            $table->timestamp('session_expires_at')->nullable();

            $table->ipAddress('issued_ip')->nullable();
            $table->ipAddress('used_ip')->nullable();
            $table->text('user_agent')->nullable();

            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['scope', 'expires_at']);
            $table->index(['scope', 'session_expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remote_entry_tokens');
    }
};
