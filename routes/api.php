<?php
declare(strict_types=1);

use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

Route::get('/players', [LeaderboardController::class, 'index'])->name('players.index');
Route::get('/players/{player}', [LeaderboardController::class, 'show'])->name('players.show');
Route::post('/players', [LeaderboardController::class, 'store'])->name('players.store');
Route::put('/players/{player}/increment', [LeaderboardController::class, 'increment'])->name('players.increment');
Route::put('/players/{player}/decrement', [LeaderboardController::class, 'decrement'])->name('players.decrement');
Route::delete('/players/{player}', [LeaderboardController::class, 'destroy'])->name('players.destroy');
Route::get('/players/group_by/points', [LeaderboardController::class, 'grouped'])->name('players.grouped');