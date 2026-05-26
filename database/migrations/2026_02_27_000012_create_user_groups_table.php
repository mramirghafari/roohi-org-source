<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->string('description', 500)->nullable();
            $table->boolean('is_active')->default(true);

            $table->decimal('attraction_stars_reward', 24, 8)->default(0);
            $table->decimal('purchase_commission_percent', 5, 2)->default(0);

            $table->string('purchase_reward_mode', 20)->default('none');
            $table->decimal('purchase_reward_toman', 24, 8)->default(0);
            $table->decimal('purchase_reward_usdt', 24, 8)->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('is_active');
            $table->index('purchase_reward_mode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_groups');
    }
};
