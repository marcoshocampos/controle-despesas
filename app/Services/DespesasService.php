<?php

namespace App\Services;

use App\Models\Despesa;

class DespesasService
{
    public function getDespesas()
    {
        $despesas = auth()->user()->despesas;

        return $despesas;
    }

    public function createDespesa($data)
    {
        $userId = auth()->user()->id;

        return Despesa::create([
            'descricao' => $data['descricao'],
            'valor' => $data['valor'],
            'data_ocorrencia' => $data['data_ocorrencia'],
            'user_id' => $userId,
        ]);
    }

    public function findDespesa($despesaId)
    {
        return Despesa::find($despesaId);
    }

    public function updateDespesa(Despesa $despesa, $data)
    {
        $despesa->update($data);

        return $despesa->update($data);
    }

    public function deleteDespesa(Despesa $despesa) 
    {
        $despesa->delete();

        return true;
    }
}
