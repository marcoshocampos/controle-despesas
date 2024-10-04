<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponseResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="SuccessResponse",
     *     type="object",
     *     title="Resposta de Sucesso",
     *     description="Formato padrão para respostas de sucesso",
     *     @OA\Property(
     *         property="success",
     *         type="boolean",
     *         description="Indica se a operação foi bem-sucedida",
     *         example=true
     *     ),
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         description="Mensagem descritiva do sucesso",
     *         example="Operation completed successfully."
     *     ),
     *     @OA\Property(
     *         property="data",
     *         type="object",
     *         description="Os dados retornados pela operação",
     *         example={
     *             "id": 1,
     *             "name": "John Doe"
     *         }
     *     )
     * )
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
     * @OA\Schema(
     *     schema="ErrorResponse",
     *     type="object",
     *     title="Resposta de Erro",
     *     description="Formato padrão para respostas de erro",
     *     @OA\Property(
     *         property="success",
     *         type="boolean",
     *         description="Indica que a operação falhou",
     *         example=false
     *     ),
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         description="Mensagem descritiva do erro",
     *         example="An error occurred."
     *     ),
     *     @OA\Property(
     *         property="error",
     *         type="string",
     *         description="Detalhe do erro",
     *         example="Invalid credentials"
     *     ),
     *     @OA\Property(
     *         property="line",
     *         type="integer",
     *         description="Linha onde ocorreu o erro (opcional)",
     *         example=45
     *     )
     * )
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
