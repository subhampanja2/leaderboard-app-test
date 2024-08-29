<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Models\Player;
use App\Models\Winner;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckHighestScorer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $highestScorer = Player::orderBy('points', 'desc')->first();

        $otherPlayersWithSameScore = Player::where('points', $highestScorer->points)
            ->where('id', '!=', $highestScorer->id)
            ->exists();

        if (!$otherPlayersWithSameScore) {
            Winner::create([
                'player_id' => $highestScorer->id,
                'points' => $highestScorer->points,
                'declared_at' => now(),
            ]);
        }
    }
}
