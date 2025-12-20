<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSpeakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $event1 = Event::find(1);
        $event2 = Event::find(2);

        $event1->speakers()->attach([1]);
        $event2->speakers()->attach([2]);
    }
}
