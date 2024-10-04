<?php

namespace Tests\Unit;

use App\Models\Despesa;
use App\Models\User;
use App\Services\DespesasService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DespesasControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function lists_despesas()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->mock(DespesasService::class, function ($mock) {
            $mock->shouldReceive('getDespesas')
                ->once()
                ->andReturn(Despesa::factory()->count(3)->make());
        });

        $response = $this->getJson('/api/despesas');

        $response->assertStatus(201);
    }

    /** @test */
    public function creates_new_despesa()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->mock(DespesasService::class, function ($mock) {
            $mock->shouldReceive('createDespesa')
                ->once()
                ->andReturn(Despesa::factory()->make());
        });
        
        $response = $this->postJson('/api/despesas', [
            'descricao' => 'Nova Despesa',
            'valor' => 150.00,
            'data_ocorrencia' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function shows_despesa()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->mock(DespesasService::class, function ($mock) {
            $mock->shouldReceive('findDespesa')
                ->once()
                ->andReturn(Despesa::factory()->make());
        });

        $response = $this->getJson('/api/despesas/1');

        $response->assertStatus(200);
    }

    /** @test */
    public function updates_despesa()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->mock(DespesasService::class, function ($mock) {
            $mock->shouldReceive('findDespesa')
                ->once()
                ->andReturn(Despesa::factory()->make());

            $mock->shouldReceive('updateDespesa')
                ->once()
                ->andReturn(Despesa::factory()->make());
        });

        $response = $this->putJson('/api/despesas/1', [
            'descricao' => 'Despesa Atualizada',
            'data_ocorrencia ' => '2024-10-03',
            'valor' => 200.00,
        ]);

        $response->assertStatus(200);
    }

    public function deletes_despesa()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->mock(DespesasService::class, function ($mock) {
            $mock->shouldReceive('findDespesa')
                ->once()
                ->andReturn(Despesa::factory()->make());

            $mock->shouldReceive('deleteDespesa')
                ->once()
                ->andReturn(true);
        });

        $response = $this->deleteJson('/api/despesas/1');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Deleted successfully.',
            ]);
    }
}
