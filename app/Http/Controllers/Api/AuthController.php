<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('name', $request->name)->first();

        if(!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'name' => ['The provided credentials are incorrect.'],
            ]);
        }
        $user->tokens()->delete();
        $token = $user->createToken('auth_token', ['user'])->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function logout(Request $request): Response
    {
        $request->user()->tokens()->delete();

        return response()->noContent();
    }
}
