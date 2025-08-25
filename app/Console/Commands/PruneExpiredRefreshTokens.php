<?php

namespace App\Console\Commands;

use App\Models\RefreshToken;
use Illuminate\Console\Command;

class PruneExpiredRefreshTokens extends Command
{
    protected $signature = 'tokens:prune';

    protected $description = 'Delete expired refresh tokens from all guards';

    public function handle(): int
    {
        $count = RefreshToken::where('expires_at', '<', now())->delete();
        $this->info("Deleted {$count} expired refresh tokens.");

        return 0;
    }
}
