<?php

namespace App\Events;

use danog\MadelineProto\API;

class DialogsHandler
{
    /**
     * @var API
     */
    protected $MadelineProto;

    /**
     * @var array
     */
    protected $dialogs = [];

    public function __construct($MadelineProto)
    {
        $this->MadelineProto = $MadelineProto;
    }

    public function getDialogs()
    {
        if (!empty($this->dialogs)) {
            return $this->dialogs;
        }

        $dialogs = $this->MadelineProto->get_dialogs();

        if (empty($dialogs)) {
            return false;
        }

        $ids = [];
        foreach ($dialogs as $dialog) {
            if ($dialog['_'] !== 'peerUser') {
                continue;
            }

            $ids[] = $dialog['user_id'];
        }

        $users = $this->MadelineProto->users->getUsers(['id' => $ids]);

        foreach ($users as $user) {
            $this->dialogs[] = [
                'name' => $user['first_name'] . (!empty($user['last_name']) ? " " . $user['last_name'] : ''),
                'id' => $user['id'],
                'phone' => $user['phone'],
            ];
        }

        var_dump($this->dialogs);

    }
}