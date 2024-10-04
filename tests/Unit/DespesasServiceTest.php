<?php

namespace Tests\Unit;

use App\Models\Despesa;
use App\Models\User;
use App\Services\DespesasService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DespesasServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $despesasService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->despesasService = new DespesasService();
    }

    /** @test */
    public function gets_despesas_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Cria despesas associadas ao usuário logado
        Despesa::factory()->count(3)->create(['user_id' => $user->id]);

        // Chama o método getDespesas do service
        $despesas = $this->despesasService->getDespesas();

        // Verifica se o retorno contém 3 despesas
        $this->assertCount(3, $despesas);
    }

    /** @test */
    public function creates_despesa_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'descricao' => 'Compra de materiais',
            'valor' => 200.00,
            'data_ocorrencia' => '2024-10-03',
        ];

        // Chama o método createDespesa
        $despesa = $this->despesasService->createDespesa($data);

        // Verifica se a despesa foi criada corretamente
        $this->assertInstanceOf(Despesa::class, $despesa);
        $this->assertDatabaseHas('despesas', [
            'descricao' => 'Compra de materiais',
            'valor' => 200.00,
            'data_ocorrencia' => '2024-10-03',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function finds_despesa_by_id()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        // Cria uma despesa
        $despesa = Despesa::factory()->create([
            'user_id' => $user->id
        ]);

        // Chama o método findDespesa
        $foundDespesa = $this->despesasService->findDespesa($despesa->id);

        // Verifica se a despesa encontrada é a correta
        $this->assertEquals($despesa->id, $foundDespesa->id);
    }

    /** @test */
    public function updates_despesa()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        // Cria uma despesa
        $despesa = Despesa::factory()->create([
            'user_id' => $user->id
        ]);

        $data = [
            'descricao' => 'Despesa atualizada',
            'valor' => 300.00,
            'data_ocorrencia' => '2024-10-04',
        ];

        // Chama o método updateDespesa
        $updatedDespesa = $this->despesasService->updateDespesa($despesa, $data);

        // Verifica se a despesa foi atualizada
        $this->assertTrue($updatedDespesa);
        $this->assertDatabaseHas('despesas', [
            'id' => $despesa->id,
            'descricao' => 'Despesa atualizada',
            'valor' => 300.00,
            'data_ocorrencia' => '2024-10-04',
        ]);
    }

    /** @test */
    public function deletes_despesa()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        // Cria uma despesa
        $despesa = Despesa::factory()->create([
            'user_id' => $user->id
        ]);

        // Chama o método deleteDespesa
        $result = $this->despesasService->deleteDespesa($despesa);

        // Verifica se a despesa foi deletada
        $this->assertTrue($result);
        $this->assertDatabaseMissing('despesas', [
            'id' => $despesa->id,
        ]);
    }
}
