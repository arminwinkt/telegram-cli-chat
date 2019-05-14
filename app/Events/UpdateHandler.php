<?php

namespace App\Events;

use danog\MadelineProto\API;
use Mockery\Exception;

class UpdateHandler
{
    /**
     * @var API
     */
    protected $MadelineProto;

    /**
     * @var int
     */
    protected $lastUpdateId = 0;

    /**
     * @var int
     */
    protected $chatId;

    /**
     * @var string
     */
    protected $chatName;

    protected $nameSpacing = 7;

    public function __construct($MadelineProto, $chatId)
    {
        $this->MadelineProto = $MadelineProto;
        $this->chatId = $chatId;
        $this->chatName = $this->getUserNameById($this->chatId);
        $this->nameSpacing = strlen($this->chatName) > $this->nameSpacing ? strlen($this->chatName) : $this->nameSpacing;
    }

    public function handleUpdates()
    {
        $updates = $this->MadelineProto->get_updates(['offset' => $this->lastUpdateId, 'limit' => 50, 'timeout' => 0]);

        if (empty($updates)) {
            return;
        }
        foreach ($updates as $update) {
            if ($update['update_id'] === $this->lastUpdateId) {
                continue;
            }

            $this->lastUpdateId = $update['update_id'];

            switch ($update['update']['_']) {
                case 'updateNewMessage':
                    $this->handleNewMessage($update);
            }
        }

        return;
    }

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

    protected function handleNewMessage($update)
    {
        if ($update['update']['message']['from_id'] !== $this->chatId) {
            return;
        }

        printf("%-{$this->nameSpacing}s", $this->chatName);

        echo " > " . $update['update']['message']['message'] . "\n";
    }

}