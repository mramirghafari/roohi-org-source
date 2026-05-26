<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class BackfillUserRefCodes extends Command
{
    protected $signature = 'users:backfill-ref-codes {--chunk=500}';
    protected $description = 'Generate 6-char uppercase alphanumeric ref_code for users';

    private function generateRefCode(int $length = 6): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $code;
    }

    public function handle(): int
    {
        $chunk = (int) $this->option('chunk');

        User::whereNull('ref_code')
            ->orderBy('id')
            ->chunkById($chunk, function ($users) {
                foreach ($users as $user) {
                    do {
                        $code = $this->generateRefCode(6);
                    } while (User::where('ref_code', $code)->exists());

                    $user->ref_code = $code;
                    $user->save();
                }
            });

        $this->info('Done: 6-char ref_code generated.');
        return self::SUCCESS;
    }
}
