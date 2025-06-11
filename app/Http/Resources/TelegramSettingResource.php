<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TelegramSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'telegram_chat_id' => $this->telegram_chat_id,
            'telegram_username' => $this->telegram_username,
            'created_at' => $this->created_at
        ];
    }
}
