<?php

namespace App\Console\Commands;

use App\Services\UniServerService;
use Illuminate\Console\Command;

class MonitorUniServerCommand extends Command
{
    protected $signature = 'monitor:uniserver';

    protected $description = 'Monitor UniServer API for new records';

    public function handle(): void
    {
        $uniServer = new UniServerService();
        $this->info('Starting UniServer monitoring...');

        while (true) {
            $newRecord = $uniServer->checkForNewRecords();
            
            if ($newRecord) {
                /*$message = $telegram->formatRecordMessage($newRecord);
                $telegram->sendNotification($message);*/
                var_dump($newRecord['DOCUMENT_NUMBER']);
                $this->info('New record detected and notification sent!');
            }

            sleep(5); // Проверяем каждые 5 секунд
        }
    }
}
