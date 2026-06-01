<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('duration_months');
            $table->unsignedBigInteger('gateway_price')->nullable();
            $table->boolean('gateway_enabled')->default(true);
            $table->unsignedBigInteger('card_to_card_price')->nullable();
            $table->boolean('card_to_card_enabled')->default(false);
            $table->string('card_number', 32)->nullable();
            $table->string('card_owner', 120)->nullable();
            $table->boolean('receipt_required')->default(true);
            $table->boolean('payer_card_required')->default(true);
            $table->boolean('paid_at_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        DB::table('subscription_plans')->insert([
            [
                'title' => 'اشتراک یک ماهه',
                'description' => 'پلن VIP یک ماهه',
                'duration_months' => 1,
                'gateway_price' => 4900000,
                'gateway_enabled' => true,
                'card_to_card_price' => 4900000,
                'card_to_card_enabled' => false,
                'receipt_required' => true,
                'payer_card_required' => true,
                'paid_at_required' => false,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'اشتراک دو ماهه',
                'description' => 'پلن VIP دو ماهه',
                'duration_months' => 2,
                'gateway_price' => 8800000,
                'gateway_enabled' => true,
                'card_to_card_price' => 8800000,
                'card_to_card_enabled' => false,
                'receipt_required' => true,
                'payer_card_required' => true,
                'paid_at_required' => false,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'اشتراک سه ماهه',
                'description' => 'پلن VIP سه ماهه',
                'duration_months' => 3,
                'gateway_price' => 11700000,
                'gateway_enabled' => true,
                'card_to_card_price' => 11700000,
                'card_to_card_enabled' => false,
                'receipt_required' => true,
                'payer_card_required' => true,
                'paid_at_required' => false,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};