<?php

namespace Database\Seeders;

use App\Models\EventRating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventRatings = [
            [
                'user_id' => 1,
                'event_id' => 1,
                'rating' => 5,
                'comment' => 'Event sangat bermanfaat!',
            ]
        ];

        foreach ($eventRatings as $eventRating) {
            EventRating::create($eventRating);
        }
    }
}
