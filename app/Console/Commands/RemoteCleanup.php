<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RemoteInstance;
use App\Services\RemoteDesktopManager;

class RemoteCleanup extends Command
{
    protected $signature = 'remote:cleanup';
    protected $description = 'Cleanup expired remote desktop containers';

    public function handle(RemoteDesktopManager $svc)
    {
        $expired = RemoteInstance::query()
            ->whereIn('status', ['running', 'starting'])
            ->where('expires_at','<=', now())
            ->get();

        foreach ($expired as $s) {
            $svc->terminateInstance($s);
        }

        return self::SUCCESS;
    }
}
