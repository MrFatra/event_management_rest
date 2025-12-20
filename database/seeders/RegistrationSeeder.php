<?php

namespace Database\Seeders;

use App\Models\Registration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registrations = [
            [
                'user_id' => 1,
                'event_id' => 1,
                'status' => 'registered',
            ]
        ];

        foreach ($registrations as $registration) {
            Registration::create($registration);
        }
    }
}
