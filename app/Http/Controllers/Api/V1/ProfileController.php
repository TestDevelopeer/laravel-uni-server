<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\TelegramSettingResource;
use App\Http\Resources\UserResource;
use App\Models\TelegramSetting;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'user' => new UserResource($user),
            'telegram' => new TelegramSettingResource($user->telegram)
        ]);
    }

    public function update(ProfileUpdateRequest $request): ?JsonResponse
    {
        $data = $request->validated();
        $userId = $request->user()->id;

        try {
            TelegramSetting::updateOrCreate(
                ['user_id' => $userId],
                [
                    'chat_id' => $data['chat_id'],
                    'username' => $data['username']
                ]
            );

            return response()->json(['success' => "ChatID #{$data['chat_id']} успешно привязан к пользователю #{$userId}"]);
        } catch (QueryException $e) {
            return response()->json(['error' => "Database error CODE {$e->getCode()}: {$e->getMessage()}"], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => "Error: {$e->getMessage()}"], $e->getCode());
        }
    }
}
