<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaderboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_player(): void
    {
        Player::factory()->count(5)->create();

        $response = $this->getJson('/api/players');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => ['*' => ['id', 'name', 'age', 'points', 'address', 'created_at', 'updated_at']],
                'meta' => [
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'links',
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total',
                ],
            ]);
    }

    public function test_can_store_a_player(): void
    {
        $playerData = [
            'name' => 'John Doe',
            'age' => 30,
            'address' => '123 Main St',
        ];

        $response = $this->postJson('/api/players', $playerData);

        $response->assertStatus(201)
            ->assertJsonFragment($playerData);
    }

    public function test_can_increment_player_points(): void
    {
        $player = Player::factory()->create(['points' => 5]);

        $response = $this->putJson("/api/players/{$player->id}/increment");

        $response->assertStatus(200)
            ->assertJsonFragment(['points' => 6]);
    }

    public function test_can_decrement_player_points(): void
    {
        $player = Player::factory()->create(['points' => 5]);

        $response = $this->putJson("/api/players/{$player->id}/decrement");

        $response->assertStatus(200)
            ->assertJsonFragment(['points' => 4]);
    }

    public function test_can_delete_player(): void
    {
        $player = Player::factory()->create();

        $response = $this->deleteJson("/api/players/{$player->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('players', ['id' => $player->id]);
    }

    public function test_can_get_players_grouped_by_points(): void
    {
        Player::factory()->create(['name' => 'Subham', 'age' => 25, 'points' => 10]);
        Player::factory()->create(['name' => 'Sourav', 'age' => 20, 'points' => 10]);
        Player::factory()->create(['name' => 'Sandip', 'age' => 30, 'points' => 5]);

        $response = $this->getJson('/api/players/group_by/points');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '10' => ['names', 'average_age'],
                '5' => ['names', 'average_age'],
            ])
            ->assertJsonFragment([
                'names' => ['Subham', 'Sourav'],
                'average_age' => 22.5,
            ]);
    }
}
