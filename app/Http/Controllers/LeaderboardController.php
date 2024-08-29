<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class LeaderboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {

        $perPage = $request->input('per_page', 15); // Default per page is 15
        $page = $request->input('page', 1); // Default page is 1

        $players = Player::orderBy('points', 'desc')->paginate($perPage, ['*'], null, $page);

        $currentPage = $players->currentPage();

        return response()->json([
            'data' => $players->items(),
            'meta' => [
                'first_page_url' => $players->url(1),
                'from' => $players->firstItem(),
                'last_page' => $players->lastPage(),
                'last_page_url' => $players->url($players->lastPage()),
                'links' => $players->links(),
                'next_page_url' => $players->nextPageUrl(),
                'path' => $players->path(),
                'per_page' => $perPage,
                'prev_page_url' => $players->previousPageUrl(),
                'to' => $players->lastItem(),
                'total' => $players->total()
            ]
        ]);
    }

    public function show(Player $player): JsonResponse
    {
        return response()->json($player);
    }

    public function store(Request $request): JsonResponse
    {
        // Validate data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:18',
            'address' => 'required|string|max:255',
        ]);

        $player = Player::create($validatedData);

        // Generate QR
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($player->address);
        $response = Http::get($qrCodeUrl);

        if ($response->successful() && $response->header('Content-Type') === 'image/png') {
            Storage::put("public/qrcodes/{$player->id}.png", $response->body());
        } else {
            return response()->json(['error' => 'Failed to generate QR code'], 500);
        }

        return response()->json($player, 201);
    }

    public function increment(Player $player): JsonResponse
    {
        $player->increment('points');
        return response()->json($player);
    }

    public function decrement(Player $player): JsonResponse
    {
        $player->decrement('points');
        return response()->json($player);
    }

    public function destroy(Player $player): JsonResponse
    {
        $player->delete();
        return response()->json(null, 204);
    }

    public function grouped(): JsonResponse
    {
        $players = Player::select('points', 'name', 'age')
            ->orderBy('points', 'desc')
            ->get()
            ->groupBy(function ($player) {
                return (int)$player->points;
            });

        $result = [];

        foreach ($players as $points => $group) {
            $averageAge = $group->avg('age');
            $names = $group->pluck('name')->all();

            $result[$points] = [
                'names' => $names,
                'average_age' => $averageAge,
            ];
        }

        return response()->json($result);
    }
}