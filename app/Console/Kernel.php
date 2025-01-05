<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\PaymentRequestCron::class,
        Commands\ResubscribeUser::class,
        Commands\RemoveExpiredSubscription::class,
        Commands\AppointmentReminderEmail::class,
        Commands\ChangeBookingStatus::class,
        Commands\PendingBookingsReminder::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('paymentreq:cron')->hourly();
//        $schedule->command('appointment:reminder')->everyThirtyMinutes();
//        $schedule->command('pending_booking:reminder')->cron('* */6 * * *');
//        $schedule->command('change_status:reminder')->cron('* * * * *');
//        $schedule->command('subscription:remove')->daily();
//        $schedule->command('subscription:update')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
