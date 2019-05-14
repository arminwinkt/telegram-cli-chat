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

    public function __construct()
    {
        $this->MadelineProto = MadelineProtoHandler::getInstance()->getMadelineProto();
    }

    /**
     * @return array|bool
     */
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

        return $this->dialogs;
    }

    /**
     * @return array
     */
    public function getDialogsMenuOptions()
    {
        $dialogs = $this->getDialogs();
        $menu = [];

        foreach ($dialogs as $dialog) {
            $menu[$dialog['id']] = $dialog['name'] . " ({$dialog['phone']})";
        }

        return $menu;
    }
}