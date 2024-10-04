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

        $requestData = [
            'descricao' => 'Nova Despesa',
            'valor' => 150.00,
            'data_ocorrencia' => now()->format('Y-m-d'),
        ];

        $this->mock(DespesasService::class, function ($mock) use ($requestData) {
            $mock->shouldReceive('createDespesa')
                ->once()
                ->andReturn(Despesa::factory()->create(array_merge($requestData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])));
        });

        $response = $this->postJson('/api/despesas', $requestData);

        $response->assertStatus(201);

        $response->assertJson([
            'data' => [
                'descricao' => 'Nova Despesa',
                'valor' => 150.00,
                'data_ocorrencia' => now()->format('Y-m-d'),
            ],
        ]);
    }

    /** @test */
    public function shows_despesa()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $despesaData = [
            'id' => 1,
            'descricao' => 'Teste de Despesa',
            'valor' => 150.00,
            'data_ocorrencia' => now()->format('Y-m-d'),
            'user_id' => $user->id,
            'created_at' => now()->toDateString(),
            'updated_at' => now()->toDateString(),
        ];

        $this->mock(DespesasService::class, function ($mock) use ($despesaData) {
            $mock->shouldReceive('findDespesa')
                ->once()
                ->andReturn(Despesa::factory()->make($despesaData));
        });

        $response = $this->getJson('/api/despesas/1');

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => 1,
                'descricao' => 'Teste de Despesa',
                'valor' => 150.00,
                'data_ocorrencia' => now()->format('Y-m-d'),
                'created_at' => now()->toDateString(),
                'updated_at' => now()->toDateString(),
            ],
        ]);
    }

    /** @test */
    public function updates_despesa()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $despesaOriginal = [
            'id' => 1,
            'descricao' => 'Despesa Original',
            'valor' => 100.00,
            'data_ocorrencia' => '2024-10-02',
            'user_id' => $user->id,
            'created_at' => now()->toDateString(),
            'updated_at' => now()->toDateString(),
        ];

        // Mock dos dados atualizados da despesa
        $despesaAtualizada = [
            'id' => 1,
            'descricao' => 'Despesa Atualizada',
            'valor' => 200.00,
            'data_ocorrencia' => '2024-10-03',
            'user_id' => $user->id,
            'created_at' => now()->toDateString(),
            'updated_at' => now()->toDateString(),
        ];

        $this->mock(DespesasService::class, function ($mock) use ($despesaOriginal, $despesaAtualizada) {
            // quando encontrar a despesa, retorna os dados originais
            $mock->shouldReceive('findDespesa')
                ->once()
                ->andReturn(Despesa::factory()->make($despesaOriginal));

            // quando atualizar a despesa, retorna os dados atualizados
            $mock->shouldReceive('updateDespesa')
                ->once()
                ->andReturn(Despesa::factory()->make($despesaAtualizada));
        });

        $response = $this->putJson('/api/despesas/1', [
            'descricao' => 'Despesa Atualizada',
            'data_ocorrencia ' => '2024-10-03',
            'valor' => 200.00,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => 1,
                'descricao' => 'Despesa Atualizada',
                'valor' => 200.00,
                'data_ocorrencia' => '2024-10-03',
                'created_at' => now()->toDateString(),
                'updated_at' => now()->toDateString(),
            ],
        ]);
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
