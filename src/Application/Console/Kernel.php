<?php

declare(strict_types=1);

namespace App\Console;

use Codefy\Framework\Console\ConsoleKernel;
use Codefy\Framework\Scheduler\Schedule;

class Kernel extends ConsoleKernel
{
    /**
     * Add your custom console commands here.
     *
     * @var array
     */
    protected array $commands = [];

    /**
     * Place all your scheduled tasks here.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        //$schedule->php(script: 'contents.php')->everyMinute();
    }

    /**
     * Place all your commands here that need to be registered
     * to your application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load($this->commands);
    }
}
