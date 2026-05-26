<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('default_commission_rules', function (Blueprint $table) {
            $table->id();
            $table->string('event', 40);
            $table->unsignedInteger('level');
            $table->decimal('stars_reward', 24, 8)->default(0);
            $table->decimal('toman_reward', 24, 8)->default(0);
            $table->decimal('usdt_reward', 24, 8)->default(0);
            $table->decimal('commission_percent', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['event', 'level'], 'default_event_level_unique');
            $table->index(['event', 'level']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('default_commission_rules');
    }
};
