<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_department_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('department', 50);
            $table->timestamps();

            $table->unique(['user_id', 'department']);
            $table->index('department');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_department_user');
    }
};
