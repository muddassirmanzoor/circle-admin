<?php


namespace App\Console\Commands;

use App\CronJobLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BaseCommand extends Command
{


    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'Base Command';

    /**
     * The console command name To Display.
     *
     * @var string
     */
    protected $commandName = 'Base Command';

    /**
     * Log Text for DB
     *
     * @var string
     */
    protected $logText = '';


    /**
     * File Log Channel
     *
     * @var string
     */
    protected $logChannel = '';

    protected function log($message, $context = [], $type = 'info'){
        Log::channel($this->logChannel)->$type($message, $context);
        $newText = '['.date('Y-m-d H:i:s').'] '.$message. ' '.(!empty($context) ? json_encode($context) : '')."\n";
        $this->logText .= $newText;
        echo $newText;
    }

    protected function InsertDBLog($success = 1){
        return CronJobLog::create([
            'name' => $this->commandName,
            'message' => $this->logText,
            'success' => $success,
        ]);
    }
}
