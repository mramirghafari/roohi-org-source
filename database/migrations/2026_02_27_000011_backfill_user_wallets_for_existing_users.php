<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('users')
            ->select('id')
            ->orderBy('id')
            ->chunk(500, function ($users) use ($now) {
                $rows = [];

                foreach ($users as $user) {
                    $rows[] = [
                        'user_id' => $user->id,
                        'toman_balance' => 0,
                        'usdt_balance' => 0,
                        'stars_balance' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if (!empty($rows)) {
                    DB::table('user_wallets')->insertOrIgnore($rows);
                }
            });
    }

    public function down(): void
    {
    }
};
