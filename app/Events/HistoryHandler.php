<?php

namespace App\Events;

use danog\MadelineProto\API;

class HistoryHandler
{
    /**
     * @var API
     */
    protected $MadelineProto;

    /**
     * @var int
     */
    protected $chatId;

    /**
     * @var array
     */
    protected $history = [];

    public function __construct($MadelineProto, $chatId)
    {
        $this->MadelineProto = $MadelineProto;
        $this->chatId = $chatId;
    }

    /**
     * @return array|bool
     */
    public function getHistory()
    {
        if (!empty($this->history)) {
            return $this->history;
        }

        $history = $this->MadelineProto->messages->getHistory([
            'peer' => $this->chatId,
            'offset_id' => 0,
            'offset_date' => 0,
            'add_offset' => 0,
            'limit' => 20,
            'max_id' => 0,
            'min_id' => 0,
        ]);

        if (empty($history['messages'])) {
            return false;
        }

        foreach ($history['messages'] as $message) {
            if ($message['_'] !== 'message') {
                continue;
            }

            $this->history[] = [
                'from' => $message['from_id'],
                'message' => $message['message'],
            ];
        }

        return $this->history;
    }

    public function showHistory()
    {
        $history = $this->getHistory();
        if (empty($history)) {
            echo "There are no messages yet.\n";
            return;
        }

        $history = array_reverse($history);

        $currentUserId = UserHandler::getInstance($this->MadelineProto)->getUser()['id'];

        foreach ($history as $message) {
            if($message['from'] === $currentUserId) {
                echo "\e[1;37;41mYou\e[0m";
            } else {
                echo "\e[1;37;44mOther\e[0m";
            }

            echo " > ";
            echo $message['message'] . "\n";
        }

    }
}