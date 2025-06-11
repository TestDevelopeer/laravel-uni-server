<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        if(app()->environment('local')) {
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
    public function sendMessage(Request $request): JsonResponse
    {
        $this->telegram->sendMessage([
            'chat_id' => $request->user()->telegram->chat_id,
            'text' => $request->message,
            'parse_mode' => 'HTML'
        ]);
        return response()->json(['status' => 'success']);
    }
}
