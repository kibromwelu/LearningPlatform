<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Identity;
use Carbon\Carbon;

class ClearIncompleteRegistrations extends Command
{
    protected $signature = 'registrations:clear-incomplete';

    protected $description = 'Clear incomplete registrations that are older than 2 days';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $twoDaysAgo = Carbon::now()->subDays(2);
        $deletedCount = Identity::whereNull('completed_at')
            ->where('created_at', '<', $twoDaysAgo)
            ->delete();
        $this->info("Deleted {$deletedCount} incomplete registrations.");
    }
}
