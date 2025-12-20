<?php

namespace Database\Seeders;

use App\Models\Speaker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpeakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $speakers = [
            [
                'name' => 'Andi Pratama',
                'bio' => 'AI Engineer',
            ],
            [
                'name' => 'Budi Santoso',
                'bio' => 'Android Developer',
            ]
        ];

        foreach ($speakers as $speaker) {
            Speaker::create($speaker);
        }
    }
}
