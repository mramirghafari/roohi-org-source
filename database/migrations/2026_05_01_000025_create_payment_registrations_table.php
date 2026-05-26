<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 150);
            $table->string('mobile', 20);
            $table->unsignedBigInteger('amount');
            $table->string('currency', 8)->default('IRT');
            $table->string('tracking_code', 32)->unique();
            $table->string('authority', 64)->nullable()->unique();
            $table->string('ref_id', 64)->nullable();
            $table->string('card_pan', 32)->nullable();
            $table->string('gateway_status', 20)->nullable();
            $table->integer('gateway_code')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('message')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['mobile', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index(['ref_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_registrations');
    }
};
