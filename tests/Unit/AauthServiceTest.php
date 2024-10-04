<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AauthServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logs_in_user_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'teste@exemplo.com',
            'senha' => Hash::make('senha-correta'),
        ]);

        $authService = new AuthService();

        $credentials = ['email' => 'teste@exemplo.com', 'senha' => 'senha-correta'];

        $result = $authService->login($credentials);

        $this->assertEquals($user->id, $result['user']->id);
        $this->assertNotNull($result['access_token']);
    }

    /** @test */
    public function fails_incorrect_credentials()
    {
        User::factory()->create([
            'email' => 'teste@exemplo.com',
            'senha' => Hash::make('senha-correta'),
        ]);

        $authService = new AuthService();

        $credentials = ['email' => 'teste@exemplo.com', 'senha' => 'senha-incorreta'];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Credentials provided are incorrect.');

        $authService->login($credentials);
    }
}
