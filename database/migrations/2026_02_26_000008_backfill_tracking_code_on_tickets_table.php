<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tickets')
            ->orderBy('id')
            ->select(['id', 'tracking_code'])
            ->chunkById(200, function ($tickets) {
                foreach ($tickets as $ticket) {
                    $code = (string) $ticket->tracking_code;

                    if (preg_match('/^\d{8}$/', $code)) {
                        continue;
                    }

                    DB::table('tickets')
                        ->where('id', $ticket->id)
                        ->update(['tracking_code' => $this->generateUniqueCode()]);
                }
            }, 'id');

        Schema::table('tickets', function (Blueprint $table) {
            $table->string('tracking_code', 8)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('tracking_code', 30)->nullable()->change();
        });
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (DB::table('tickets')->where('tracking_code', $code)->exists());

        return $code;
    }
};
