<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDespesaRequest;
use App\Http\Requests\UpdateDespesaRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\DespesaResource;
use App\Models\Despesa;
use App\Services\DespesasService;
use Exception;

class DespesasController extends Controller
{
    protected $despesasService;

    public function __construct(DespesasService $despesasService)
    {
        $this->despesasService = $despesasService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = auth()->user();

            $despesas = $this->despesasService->getDespesas($user->id);

            return ApiResponseResource::success(DespesaResource::collection($despesas), 'Success.', 201);
        } catch (Exception $e) {
            return ApiResponseResource::error($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDespesaRequest $request)
    {
        try {
            $this->authorize('create', Despesa::class);

            $despesa = $this->despesasService->createDespesa($request->validated());

            return ApiResponseResource::success(new DespesaResource($despesa), 'Success.', 201);
        } catch (Exception $e) {
            return ApiResponseResource::error($e);
        }
    }

    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
