<?php

namespace App\Telegram\Bot\Commands;

use App\Models\TelegramSetting;
use App\Models\User;
use Telegram\Bot\Commands\Command;

class SetChatIdCommand extends Command
{
    protected string $name = 'setchatid';

    protected string $pattern = '{user_id}';

    /**
     * @var array Command Aliases
     */
    protected array $aliases = ['id', 'setid', 'chatid', 'set'];

    /**
     * @var string Command Description
     */
    protected string $description = 'Закрепить ID данного чата за пользователем';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $userId = $this->argument('user_id');
        $chatId = $this->getUpdate()->getMessage()->chat->id;
        $userName = $this->getUpdate()->getMessage()->from->username;

        if (!$userId) {
            $this->replyWithMessage([
                'text' => "Укажите ID пользователя из базы сразу после команды или впишите данный ChatID вручную в панели администратора: {$chatId}"
            ]);
        } else {
            $user = User::find($userId);
            if (!$user) {
                $this->replyWithMessage([
                    'text' => "Пользователь #{$userId} не найден в базе!"
                ]);
            } else {
                TelegramSetting::where('user_id', $userId)->updateOrCreate([
                   'user_id' => $userId,
                   'telegram_chat_id' => $chatId,
                   'telegram_username' => $userName
                ]);

                $this->replyWithMessage([
                    'text' => "ChatID: {$chatId} успешно привязан к пользователю #{$userId}"
                ]);
            }
        }
    }
}
