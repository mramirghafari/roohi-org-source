<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('remote_instances', function (Blueprint $table) {
            $table->id();

            // ارتباط با توکن موجود
            $table->unsignedBigInteger('remote_access_token_id');

            // instance که تو URL میاد
            $table->string('instance', 64)->unique();

            // اسم کانتینر Docker
            $table->string('container_name', 128)->unique();

            // پورتی که فقط روی localhost bind شده
            $table->unsignedInteger('internal_port');

            // زمان انقضا
            $table->timestamp('expires_at')->index();

            $table->timestamps();

            $table->foreign('remote_access_token_id')
                ->references('id')
                ->on('remote_access_tokens')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remote_instances');
    }
};
