<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_expiry_sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('subscribe_id');
            $table->unsignedTinyInteger('days_left');
            $table->date('expires_on');
            $table->string('mobile', 30);
            $table->string('template', 80)->default('XdayExpiryDateRoohiTrade');
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->index(['user_id', 'days_left']);
            $table->unique(['subscribe_id', 'days_left', 'expires_on'], 'uniq_subscribe_days_expiry');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_expiry_sms_logs');
    }
};
