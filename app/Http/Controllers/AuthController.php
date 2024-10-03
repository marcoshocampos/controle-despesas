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

    public function register(RegisterUserRequest $request)
    {
        try {
            $user = $this->authService->register($request->validated());

            return ApiResponseResource::success(new UserResource($user), 'Register successful.', 201);
        } catch (Exception $e) {
            return ApiResponseResource::error($e);
        }
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $result  = $this->authService->login($request->validated());

            return ApiResponseResource::success([
                'user' => new UserResource($result['user']),
                'access_token' => $result['access_token'],
                'token_type' => $result['token_type'],
            ], 'Login successful.', 201);
        } catch (Exception $e) {
            return ApiResponseResource::error($e);
        }
    }

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
