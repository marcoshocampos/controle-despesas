<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DespesaResource extends JsonResource
{

    /**
     * @OA\Schema(
     *     schema="DespesaResource",
     *     type="object",
     *     title="Despesa",
     *     description="Formato de resposta para a entidade Despesa",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="ID da despesa",
     *         example=1
     *     ),
     *     @OA\Property(
     *         property="descricao",
     *         type="string",
     *         description="Descrição da despesa",
     *         example="Compra de material de escritório"
     *     ),
     *     @OA\Property(
     *         property="valor",
     *         type="number",
     *         description="Valor da despesa",
     *         example=150.00
     *     ),
     *     @OA\Property(
     *         property="data_ocorrencia",
     *         type="string",
     *         format="date",
     *         description="Data em que ocorreu a despesa",
     *         example="2024-10-03"
     *     ),
     *     @OA\Property(
     *         property="created_at",
     *         type="string",
     *         format="date",
     *         description="Data em que a despesa foi criada",
     *         example="2024-10-01"
     *     ),
     *     @OA\Property(
     *         property="updated_at",
     *         type="string",
     *         format="date",
     *         description="Data em que a despesa foi atualizada",
     *         example="2024-10-02"
     *     )
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'data_ocorrencia' => $this->data_ocorrencia->toDateString(),
            'created_at' => $this->created_at->toDateString(),
            'updated_at' => $this->updated_at->toDateString(),
        ];
    }
}
