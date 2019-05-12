<?php

namespace App\Events;

class UpdateHandler
{
    protected $MadelineProto;

    protected $lastUpdateId = 0;

    public function __construct($MadelineProto)
    {
        $this->MadelineProto = $MadelineProto;
    }

    public function handleUpdates()
    {
        $updates = $this->MadelineProto->get_updates(['offset' => $this->lastUpdateId, 'limit' => 50, 'timeout' => 0]);

        if (empty($updates)) {
            return;
        }

        foreach ($updates as $update) {
            $this->lastUpdateId = $update['update_id'] + 1;

            switch ($update['update']['_']) {
                case 'updateNewMessage':
                    $this->handleNewMessage($update);
            }
        }
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
        $user = $this->getUserNameById($update['update']['message']['from_id']);

        echo $user . " > " . $update['update']['message']['message'] . "\n";
    }

}