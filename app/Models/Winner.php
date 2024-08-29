<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'points',
        'declared_at',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
