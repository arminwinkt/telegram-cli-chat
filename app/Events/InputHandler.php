<?php

namespace App\Events;

use Amp\Loop;
use danog\MadelineProto\API;

class InputHandler
{
    /**
     * @var API
     */
    protected $MadelineProto;

    /**
     * @var int
     */
    protected $chatId;

    public function __construct($MadelineProto, $chatId)
    {
        $this->MadelineProto = $MadelineProto;
        $this->chatId = $chatId;
    }

    public function handleInput()
    {
        $line = readline("Command: ");

        var_dump($line);
    }
}