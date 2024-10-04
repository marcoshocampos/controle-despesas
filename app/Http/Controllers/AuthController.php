<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registrar novo usuário",
     *     tags={"Autenticação"},
     *     description="Registra um novo usuário na aplicação",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="nome",
     *                 type="string",
     *                 description="Nome completo do usuário",
     *                 example="John Doe"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 description="Endereço de e-mail do usuário",
     *                 example="johndoe@example.com"
     *             ),
     *             @OA\Property(
     *                 property="senha",
     *                 type="string",
     *                 description="Senha do usuário",
     *                 example="password123"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registro bem-sucedido",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function register(RegisterUserRequest $request)
    {
        try {
            $user = $this->authService->register($request->validated());

            return ApiResponseResource::success(new UserResource($user), 'Register successful.', 201);
        } catch (Exception $e) {
            return ApiResponseResource::error($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login do usuário",
     *     tags={"Autenticação"},
     *     description="Realiza login do usuário e retorna o token de acesso",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 description="O endereço de e-mail do usuário",
     *                 example="johndoe@example.com"
     *             ),
     *             @OA\Property(
     *                 property="senha",
     *                 type="string",
     *                 description="A senha do usuário",
     *                 example="password123"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas"
     *     )
     * )
     */
    public function login(LoginUserRequest $request)
    {
        try {
            $result = $this->authService->login($request->validated());

            return ApiResponseResource::success([
                'user' => new UserResource($result['user']),
                'access_token' => $result['access_token'],
                'token_type' => $result['token_type'],
            ], 'Login successful.', 201);
        } catch (Exception $e) {
            return ApiResponseResource::error($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout do usuário",
     *     tags={"Autenticação"},
     *     description="Realiza o logout do usuário e revoga o token de acesso",
     *     @OA\Response(
     *         response=201,
     *         description="Logout bem-sucedido"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return ApiResponseResource::success([], 'Logout successful.', 201);
        } catch (Exception $e) {
            return ApiResponseResource::error($e);
        }
    }
}
