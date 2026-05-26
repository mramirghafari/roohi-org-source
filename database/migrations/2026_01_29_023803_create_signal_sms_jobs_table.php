<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('signal_sms_jobs')) {
            return;
        }

        Schema::create('signal_sms_jobs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('signal_id')->constrained('agt_signals')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('status')->default(0); // 0 pending, 1 sent, 2 failed
            $table->unsignedTinyInteger('attempts')->default(0);

            $table->timestamp('scheduled_at')->nullable()->index();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            $table->text('last_error')->nullable();

            $table->timestamps();

            $table->unique(['signal_id', 'user_id']);
            $table->index(['status', 'id']);
            $table->index(['signal_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signal_sms_jobs');
    }
};
