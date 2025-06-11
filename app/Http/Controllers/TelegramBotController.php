<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    /**
     * @throws TelegramSDKException
     */
    public function webhook(Request $request): JsonResponse
    {
        $update = Telegram::commandsHandler(true);
        Log::debug('UPDATE: ', [$update]);
        // Обработка команды /start с параметром
        if (isset($update['message']['text']) && str_starts_with($update['message']['text'], '/login')) {
            $text = $update['message']['text'];
            $parts = explode(' ', $text);

            if (count($parts) > 1) {
                $data = $parts[1]; // Это будет DATA123
                $user = User::find($data);

                if ($user) {
                    $user->update([
                        'telegram_id' => $update['message']['from']['id'],
                        'telegram_chat_id' => $update['message']['chat']['id'],
                        'telegram_username' => $update['message']['from']['username'],
                    ]);

                    Telegram::sendMessage([
                        'chat_id' => $update['message']['chat']['id'],
                        'text' => "Успешно: аккаунт привязан"
                    ]);
                } else {
                    Telegram::sendMessage([
                        'chat_id' => $update['message']['chat']['id'],
                        'text' => "Ошибка: пользователь не найден"
                    ]);
                }
                // Здесь можно обработать $data
                // Например, сохранить в базу или отправить ответ
                //

                // Дополнительная обработка (например, очередь)
                // ProcessData::dispatch($data)->onQueue('telegram');
            }
        }

        return response()->json(['status' => 'success']);
    }
}
