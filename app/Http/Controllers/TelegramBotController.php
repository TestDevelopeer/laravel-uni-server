<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function webhook(): JsonResponse
    {
        Telegram::commandsHandler(true);

        return response()->json(['status' => 'success']);
    }

    /**
     * @throws TelegramSDKException
     */
    public function setWebhook(): JsonResponse
    {
        $response = Telegram::setWebhook(['url' => config('telegram.bots.mybot.webhook_url')]);

        return response()->json($response);
    }
}
