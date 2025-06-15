<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TelegramSendMessageRequest;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\HttpClients\GuzzleHttpClient;

class TelegramBotController extends Controller
{
    protected Api $telegram;

    /**
     * @throws TelegramSDKException
     */
    public function __construct()
    {
        if (app()->environment('local')) {
            $this->telegram = new Api(config('telegram.bots.mybot.token'), false, new GuzzleHttpClient(new Client([
                'verify' => false,
            ])));
        } else {
            $this->telegram = new Api(config('telegram.bots.mybot.token'));
        }
    }

    /**
     * @throws TelegramSDKException
     */
    public function sendMessage(TelegramSendMessageRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (!$request->user()->telegram) {
            return response()->json(['error' => 'У пользователя не привязан Telegram ChatID'], 400);
        }

        $this->telegram->sendMessage([
            'chat_id' => $request->user()->telegram->chat_id,
            'text' => $data['message'],
            'parse_mode' => 'HTML'
        ]);

        return response()->json(['status' => 'success']);
    }
}
