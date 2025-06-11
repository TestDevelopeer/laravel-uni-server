<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TelegramSettingResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'user' => new UserResource($user),
            'telegram' => $user->telegram ? new TelegramSettingResource($user->telegram) : null
        ]);
    }
}
