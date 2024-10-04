<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDespesaRequest;
use App\Http\Requests\UpdateDespesaRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\DespesaResource;
use App\Models\Despesa;
use App\Notifications\StoreDespesaNotification;
use App\Services\DespesasService;
use Exception;

/**
 * @OA\Info(
 *     title="API de Despesas",
 *     version="1.0",
 *     description="Documentação da API de Despesas utilizando Swagger OpenAPI",
 * )
 */
class DespesasController extends Controller
{
    protected $despesasService;

    public function __construct(DespesasService $despesasService)
    {
        $this->despesasService = $despesasService;
    }

    /**
     * @OA\Get(
     *     path="/api/despesas",
     *     summary="Listar todas as despesas",
     *     tags={"Despesas"},
     *     description="Retorna a lista de despesas",
     *     @OA\Response(
     *         response=201,
     *         description="Successo",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     */
    public function index()
    {
        try {
            $despesas = $this->despesasService->getDespesas();

            return ApiResponseResource::success(DespesaResource::collection($despesas), 'Success.', 201);
        } catch (Exception $e) {
            return ApiResponseResource::error($e);
        }
    }

    /**
     * @OA\Schema(
     *     schema="StoreDespesaRequest",
     *     type="object",
     *     title="Requisição para criar uma nova despesa",
     *     description="Corpo da requisição para criar uma nova despesa",
     *     required={"descricao", "valor", "data_ocorrencia"},
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
     *         description="Data da ocorrência da despesa",
     *         example="2024-10-03"
     *     )
     * )
     */
    public function store(StoreDespesaRequest $request)
    {
        try {
            $this->authorize('create', Despesa::class);

            $despesa = $this->despesasService->createDespesa($request->validated());

            auth()->user()->notify(new StoreDespesaNotification($despesa));

            return ApiResponseResource::success(new DespesaResource($despesa), 'Success.', 201);
        } catch (Exception $e) {
            return ApiResponseResource::error($e);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/despesas/{id}",
     *     summary="Exibir uma despesa específica",
     *     tags={"Despesas"},
     *     description="Retorna os dados de uma despesa específica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da despesa",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/DespesaResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Despesa não encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $despesa = $this->despesasService->findDespesa($id);

            if (!$despesa) {
                return ApiResponseResource::error([], 'Not found.', 404);
            }

            $this->authorize('view', $despesa);

            return ApiResponseResource::success(new DespesaResource($despesa));
        } catch (Exception $e) {
            return ApiResponseResource::error($e);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/despesas/{id}",
     *     summary="Atualizar uma despesa",
     *     tags={"Despesas"},
     *     description="Atualiza uma despesa existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da despesa",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="descricao",
     *                 type="string",
     *                 description="Descrição da despesa",
     *                 example="Compra de material de escritório"
     *             ),
     *             @OA\Property(
     *                 property="valor",
     *                 type="number",
     *                 description="Valor da despesa",
     *                 example=200.00
     *             ),
     *             @OA\Property(
     *                 property="data_ocorrencia",
     *                 type="string",
     *                 format="date",
     *                 description="Data da ocorrência da despesa",
     *                 example="2024-10-03"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Despesa atualizada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/DespesaResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Despesa não encontrada"
     *     )
     * )
     */
    public function update(UpdateDespesaRequest $request, $id)
    {
        try {
            $despesa = $this->despesasService->findDespesa($id);

            if (!$despesa) {
                return ApiResponseResource::error([], 'Not found.', 404);
            }

            $this->authorize('update', $despesa);

            $despesa = $this->despesasService->updateDespesa($despesa, $request->validated());

            return ApiResponseResource::success(new DespesaResource($despesa), 'Updated successfully.');
        } catch (Exception $e) {
            return ApiResponseResource::error($e);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/despesas/{id}",
     *     summary="Deletar uma despesa",
     *     tags={"Despesas"},
     *     description="Remove uma despesa",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da despesa",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Despesa não encontrada"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $despesa = $this->despesasService->findDespesa($id);

            if (!$despesa) {
                return ApiResponseResource::error([], 'Not found.', 404);
            }

            $this->authorize('delete', $despesa);

            $this->despesasService->deleteDespesa($despesa);

            return ApiResponseResource::success([], 'Deleted successfully.');
        } catch (Exception $e) {
            return ApiResponseResource::error($e);
        }
    }
}
