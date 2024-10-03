<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponseResource extends JsonResource
{
    /**
     * Retorna uma resposta de sucesso formatada.
     *
     * @param mixed $data Os dados a serem retornados na resposta
     * @param string $message Uma mensagem de sucesso opcional
     * @param int $status Código de status HTTP (padrão 200)
     * @return JsonResponse
     */
    public static function success($data, $message = 'Operation completed successfully.', $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Retorna uma resposta de erro formatada.
     *
     * @param string $message A mensagem de erro
     * @param int $status Código de status HTTP (padrão 500)
     * @param mixed $errors Erros adicionais (opcional)
     * @return JsonResponse
     */
    public static function error($error = null, $message = 'An error occurred.', $status = 500): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($error) {
            $response['error'] = $error->getMessage();
            $response['line'] = $error->getLine();
        }

        return response()->json($response, $status);
    }
}
