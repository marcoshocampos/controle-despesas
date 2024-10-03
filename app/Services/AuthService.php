<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data)
    {
        return User::create([
            'nome' => $data['nome'],
            'email' => $data['email'],
            'senha' => Hash::make($data['senha']),
        ]);
    }

    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        // verifica se o usuário existe e a senha está correta
        if (!$user || !Hash::check($data['senha'], $user->senha)) {
            throw new Exception('Credentials provided are incorrect.');
        }

        // gera o token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }
}
