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
     * @var string
     */
    protected $userName;

    /**
     * @var array
     */
    protected $history = [];

    public function __construct($chatId)
    {
        $this->MadelineProto = MadelineProtoHandler::getInstance()->getMadelineProto();
        $this->chatId = $chatId;
        $this->userName = $this->getUserNameById($chatId);
    }

    /**
     * @param $id
     * @return string
     */
    protected function getUserNameById($id)
    {
        if (empty($id)) {
            return '';
        }

        $users = $this->MadelineProto->users->getUsers(['id' => [$id]]);

        if (empty($users) || empty($users[0])) {
            return $id;
        }

        return $users[0]['first_name'] . (!empty($users[0]['last_name']) ? " " . $users[0]['last_name'] : '');
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
            'limit' => 30,
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

        $spacing = UserHandler::getInstance()->getChatDetailLength();

        foreach ($history as $message) {
            $messageString = '';
            if ($message['from'] === $currentUserId) {
                $messageString .= "You";
            } else {
                $messageString .= $this->userName;
            }

            printf("%-{$spacing}s", $messageString);
            echo " > " . $message['message'] . "\n";
        }

    }
}