<?php

namespace App\Commands;

use App\Events\DialogsHandler;
use App\Events\UpdateHandler;
use App\Events\UserHandler;
use danog\MadelineProto\API;
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

    protected $MadelineProto = null;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->MadelineProto = new API(config('telegram.sessions.path'), config('telegram', []));
        $this->MadelineProto->start();

        echo "\nSuccessfully booted Telegram.\n\n";

        // get current logged in user
        $userHandler = new UserHandler($this->MadelineProto);
        $userHandler->showUserName();

        // show selection for chat
        $dialogHandler = new DialogsHandler($this->MadelineProto);
        $dialogHandler->getDialogs();

        // check for new messages
        $updateHandler = new UpdateHandler($this->MadelineProto);
        while (true) {
            $updateHandler->handleUpdates();
        }
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
