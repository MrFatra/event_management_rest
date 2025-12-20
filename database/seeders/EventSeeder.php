<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Webinar AI 2025',
                'description' => 'Pembahasan tren AI terbaru',
                'category_id' => 1,
                'event_type' => 'webinar',
                'start_date' => now()->toDateString(),
                'start_time' => '09:00',
                'end_time' => '12:00',
                'is_online' => true,
                'meeting_link' => 'https://zoom.us/example',
                'price' => 0,
            ],
            [
                'title' => 'Workshop Android Kotlin',
                'description' => 'Belajar Android Kotlin dari dasar',
                'category_id' => 1,
                'event_type' => 'workshop',
                'start_date' => now()->addDays(3)->toDateString(),
                'start_time' => '10:00',
                'end_time' => '16:00',
                'is_online' => false,
                'location' => 'Jakarta',
                'price' => 150000,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
