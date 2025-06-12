<?php

namespace App\Http\Controllers\Api;

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
            'telegram' => $user->telegram ? new TelegramSettingResource($user->telegram) : null
        ]);
    }

    public function update(ProfileUpdateRequest $request): ?JsonResponse
    {
        $data = $request->validated();
        $userId = $request->user()->id;

        try {
            TelegramSetting::updateOrCreate(['user_id' => $userId], ['chat_id' => $data['chat_id'], 'username' => $data['username']]);
            return response()->json(['success' => "ChatID #{$data['chat_id']} успешно привязан к пользователю #{$userId}"], );
        } catch (QueryException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            return response()->json(['error' => "Database error CODE {$errorCode}: {$errorMessage}"], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], $e->getCode());
        }
    }
}
