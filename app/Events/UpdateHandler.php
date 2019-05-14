<?php

namespace App\Events;

use App\Commands\ChatCommand;
use danog\MadelineProto\API;

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

    /**
     * @var ChatCommand
     */
    protected $notificationHandler = null;

    public function __construct($chatId, $notificationHandler = null)
    {
        $this->MadelineProto = MadelineProtoHandler::getInstance()->getMadelineProto();
        $this->chatId = $chatId;
        $this->chatName = $this->getUserNameById($this->chatId);
        $this->notificationHandler = $notificationHandler;
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

        $message = $update['update']['message']['message'];

        $spacing = UserHandler::getInstance()->getChatDetailLength();

        printf("%-{$spacing}s", $this->chatName);

        echo " > " . $message . "\n";

        if (!empty($this->notificationHandler)) {
            $this->notificationHandler->notify("CLI Chat: " . $this->chatName, $message, resource_path('logo.png'));
        }
    }

}