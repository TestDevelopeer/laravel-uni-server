<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'type' => 'telegrambot',
                'status' => 1
            ],
            [
                'type' => 'test',
                'status' => 0
            ]
        ];


        foreach ($settings as $setting) {
            Setting::factory()->create($setting);
        }
    }
}
