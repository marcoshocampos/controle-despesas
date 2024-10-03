<?php

namespace App\Services;

use App\Models\Despesa;

class DespesasService
{
    public function getDespesas($userId)
    {
        return Despesa::where('id_user', $userId)->get();
    }

    public function createDespesa($data)
    {
        $userId = auth()->user()->id;

        return Despesa::create([
            'descricao' => $data['descricao'],
            'valor' => $data['valor'],
            'data_ocorrencia' => $data['data_ocorrencia'],
            'id_user' => $userId,
        ]);
    }

    public function findDespesa($despesaId)
    {
        return Despesa::find($despesaId);
    }

    public function updateDespesa(Despesa $despesa, $data)
    {
        $despesa->update($data);

        return $despesa;
    }

    public function deleteDespesa(Despesa $despesa) 
    {
        $despesa->delete();

        return true;
    }
}
