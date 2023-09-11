<?php

declare(strict_types=1);

namespace App\Application\Console;

use Codefy\Framework\Console\ConsoleKernel;
use Codefy\Framework\Scheduler\Schedule;

class Kernel extends ConsoleKernel
{
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
        $commands = $this->codefy->make('codefy.config')->getConfigKey('app.commands');

        foreach ($commands as $command) {
            $command = $this->codefy->make($command);
            $this->registerCommand($command);
        }
    }
}
