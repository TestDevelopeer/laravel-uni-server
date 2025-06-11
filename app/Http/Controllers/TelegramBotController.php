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

        return response()->json(['status' => 'success']);
    }

    /**
     * @throws TelegramSDKException
     */
    public function setWebhook(Request $request): JsonResponse
    {
        $response = Telegram::setWebhook(['url' => config('telegram.bots.mybot.webhook_url')]);

        return response()->json($response);
    }
}
