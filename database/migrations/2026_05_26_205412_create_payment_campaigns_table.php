<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('payment_campaigns')) {
            Schema::create('payment_campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug', 100)->unique();
                $table->unsignedBigInteger('amount');
                $table->string('currency', 8)->default('IRT');
                $table->text('description')->nullable();
                $table->unsignedBigInteger('original_price')->nullable();
                $table->unsignedBigInteger('current_price')->nullable();
                $table->unsignedInteger('capacity')->default(0);
                $table->string('button_text', 80)->nullable();
                $table->string('success_message', 255)->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });

            return;
        }

        Schema::table('payment_campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_campaigns', 'original_price')) {
                $table->unsignedBigInteger('original_price')->nullable()->after('description');
            }
            if (!Schema::hasColumn('payment_campaigns', 'current_price')) {
                $table->unsignedBigInteger('current_price')->nullable()->after('original_price');
            }
            if (!Schema::hasColumn('payment_campaigns', 'capacity')) {
                $table->unsignedInteger('capacity')->default(0)->after('current_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payment_campaigns')) {
            Schema::table('payment_campaigns', function (Blueprint $table) {
                if (Schema::hasColumn('payment_campaigns', 'capacity')) {
                    $table->dropColumn('capacity');
                }
                if (Schema::hasColumn('payment_campaigns', 'current_price')) {
                    $table->dropColumn('current_price');
                }
                if (Schema::hasColumn('payment_campaigns', 'original_price')) {
                    $table->dropColumn('original_price');
                }
            });
        }
    }
};
