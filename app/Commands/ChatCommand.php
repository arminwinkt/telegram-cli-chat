<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ChatCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'chat';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start the chat';

    protected $myText = null;

    protected $MadelineProto = null;

    protected $lastUpdate = null;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->MadelineProto = new \danog\MadelineProto\API(config('telegram.sessions.path'), config('telegram', []));

        $this->MadelineProto->start();

        $me = $this->MadelineProto->get_self();

        var_dump($me);
    }


    /**
     * Define the command's schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
