<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramSetting extends Model
{
    protected $fillable = [
        'user_id',
        'telegram_chat_id',
        'telegram_username',
    ];
}
