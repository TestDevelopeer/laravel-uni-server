<?php

namespace App\Services;

use App\Models\Journal;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class UniServerService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.uniserver.api_url'),
            'timeout' => 10.0,
        ]);
    }

    public function checkForNewRecords()
    {
        $currentRecord = $this->getLastRecord();

        if (!$currentRecord) {
            return null;
        }

        $lasRecord = Journal::first();

        if (!$lasRecord) {
            Journal::create([
                'identification' => $currentRecord['ID'],
                'code' => $currentRecord['CODE'],
            ]);

            return null;
        }

        if ($currentRecord['ID'] !== $lasRecord['identification']) {
            $lasRecord['identification'] = $currentRecord['ID'];
            $lasRecord['code'] = $currentRecord['CODE'];
            $lasRecord->save();
            $lasRecord->fresh();

            return $currentRecord;
        }

        return null;
    }

    public function getLastRecord()
    {
        try {
            $response = $this->client->get("/core/SendMsg", [
                'query' => [
                    'Name' => 'AutoScaleJournal1_GetRecords',
                    'Value' => json_encode(['Filter' => [], 'MaxRows' => 1], JSON_THROW_ON_ERROR),
                    'auth_user' => config('user.name'),
                    'auth_password' => config('user.password'),
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            if (!empty($data) && is_array($data)) {
                return $data[0];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching data from UniServer API: ' . $e->getMessage());
            return null;
        } catch (GuzzleException $e) {
            Log::error('GuzzleException Error fetching data from UniServer API: ' . $e->getMessage());
            return null;
        }
    }
}
