<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    public function chat(Request $request): ?JsonResponse
    {
        $data = $request->validate([
            'chat_id' => 'required|integer',
            'username' => 'string|nullable',
        ]);

        $user = $request->user();

        try {
            TelegramSetting::updateOrCreate(['user_id' => $user->id], ['chat_id' => $data['chat_id'], 'username' => $data['username']]);

            // Успешное выполнение
            return response()->json(['success' => 'ChatID успешно привязан к пользователю'], );
        } catch (QueryException $e) {
            // Ошибка базы данных (например, дубликат ключа, синтаксическая ошибка и т. д.)
            $errorCode = $e->getCode(); // Код ошибки SQL (зависит от СУБД)
            $errorMessage = $e->getMessage();

            // Например, для MySQL:
            // - 1062: Duplicate entry (ошибка дублирования уникального ключа)
            // - 1452: Cannot add or update a child row (ошибка внешнего ключа)

            return response()->json(['error' => 'Database error: ' . $errorMessage], 500);
        } catch (\Exception $e) {
            // Другие исключения (например, проблемы с валидацией)
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
