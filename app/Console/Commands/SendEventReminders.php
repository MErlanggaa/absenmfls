<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\User;
use App\Notifications\UpcomingEventReminder;
use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notifications for events happening tomorrow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = now()->addDay()->startOfDay();
        $endOfTomorrow = now()->addDay()->endOfDay();

        // Find events happening tomorrow with reminder enabled
        $events = Event::where('reminder_enabled', true)
            ->where('is_active', true)
            ->whereBetween('event_date', [$tomorrow, $endOfTomorrow])
            ->get();

        if ($events->isEmpty()) {
            $this->info('No events tomorrow. No reminders sent.');
            return;
        }

        foreach ($events as $event) {
            // Get all active users (or event participants if defined)
            $users = User::where('is_active', true)->get();

            foreach ($users as $user) {
                $user->notify(new UpcomingEventReminder($event));
            }

            $this->info("Sent reminders for event: {$event->name} to {$users->count()} users.");
        }

        $this->info('All reminders sent successfully.');
    }
}
