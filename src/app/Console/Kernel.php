<?php

namespace App\Console;

use App\Notifications\ReservationReminder;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [];

    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $today = Carbon::today();

            $reservations = Reservation::whereDate('reservation_date', $today)->get();

            foreach ($reservations as $reservation) {
                if ($reservation->user) {
                    $reservation->user->notify(new ReservationReminder($reservation));
                }
            }
        })->dailyAt('08:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
