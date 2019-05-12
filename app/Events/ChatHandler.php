<?php

namespace App\Events;

use danog\MadelineProto\EventHandler;

class ChatHandler extends EventHandler
{
    protected $myText = null;

    public function onAny($update)
    {
        if (isset($update['message']['out']) && $update['message']['out']) {
            return;
        }

        var_dump($update);
    }

    public function onLoop()
    {
        \danog\MadelineProto\Logger::log("Working...");
    }
}