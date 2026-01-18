<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Console\Command;

class CheckAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check attendance for events that are already ended';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::where('end_date', '<', now())->get();

        $registrationCount = 0;
        $eventCount = 0;

        foreach ($events as $event) {
            $registrations = Registration::where('event_id', $event->id)->get();

            foreach ($registrations as $registration) {
                $registration->update([
                    'status' => 'attended'
                ]);
            }
            $eventCount++;
            $registrationCount = count($registrations);
        }

        $this->info('Event Ended Count: ' . $eventCount);
        $this->info('Registration Attended Count: ' . $registrationCount);
    }
}
