<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registers_user()
    {
        $this->withoutExceptionHandling();

        // mock do AuthService
        $this->mock(AuthService::class, function ($mock) {
            $mock->shouldReceive('register')
                ->once()
                ->andReturn(User::factory()->make());
        });

        // simula a requisição HTTP
        $response = $this->postJson('/api/register', [
            'nome' => 'Usuario Teste',
            'email' => 'teste@exemplo.com',
            'senha' => 'senha-correta'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'nome',
                    'email',
                ],
                'message',
            ]);
    }

    /** @test */
    public function logs_in_user()
    {
        $this->mock(AuthService::class, function ($mock) {
            $mock->shouldReceive('login')
                ->once()
                ->andReturn([
                    'user' => User::factory()->make(),
                    'access_token' => 'fake-token',
                    'token_type' => 'Bearer',
                ]);
        });

        $response = $this->postJson('/api/login', [
            'email' => 'teste@exemplo.com',
            'senha' => 'senha-correta'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id',
                        'nome',
                        'email',
                    ],
                    'access_token',
                    'token_type',
                ],
                'message',
            ]);
    }
}
