<?php

namespace App\Events;

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

    public function __construct($chatId)
    {
        $this->MadelineProto = MadelineProtoHandler::getInstance()->getMadelineProto();
        $this->chatId = $chatId;
    }

    public function handleInput($line)
    {
        if (empty(trim($line))) {
            return;
        }

        $this->MadelineProto->messages->sendMessage(['peer' => $this->chatId, 'message' => $line]);
    }
}