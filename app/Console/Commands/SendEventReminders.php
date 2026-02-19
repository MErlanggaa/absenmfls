<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
    protected $description = 'Send push notifications for events happening today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Find events happening today
        $events = Event::whereDate('event_date', $today)->get();

        if ($events->isEmpty()) {
            $this->info('Tidak ada agenda untuk hari ini.');
            return;
        }

        $this->info("Ditemukan " . $events->count() . " agenda untuk hari ini. Mengirim pengingat...");

        foreach ($events as $event) {
            // Logic: Notify all active users, or filter by department if needed
            // For now, let's notify all active users
            $users = User::where('is_active', true)->get();
            
            foreach ($users as $user) {
                try {
                    $user->notify(new EventReminder($event));
                    $this->line("Mengirim pengingat untuk agenda '{$event->name}' ke user #{$user->id}");
                } catch (\Exception $e) {
                    Log::error("Gagal mengirim pengingat ke user #{$user->id}: " . $e->getMessage());
                }
            }
        }

        $this->info('Semua pengingat telah dikirim.');
    }
}
