<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Player;

class ResetScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leaderboard:reset-scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset-scores will reset all scores from players';

    /**
     * Execute the console command.
     */

    public function handle(): void
    {
        Player::query()->update(['points' => 0]);
        $this->info('All scores have been reset to 0.');
    }
}
