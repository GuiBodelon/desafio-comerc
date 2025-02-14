<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $this->generateTokenResponse($user);
    }

    public function login(array $credentials): ?array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        return $this->generateTokenResponse($user);
    }

    public function logout(?User $user): void
    {
        if ($user) {
            $user->tokens()->delete(); // Revoga todos os tokens do usuário
        }
    }

    private function generateTokenResponse(User $user): array
    {
        return [
            'user' => $user,
            'token' => $user->createToken('pastelaria')->plainTextToken,
        ];
    }
}
