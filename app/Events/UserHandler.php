<?php

namespace App\Events;

use danog\MadelineProto\API;

class UserHandler
{
    /**
     * @var API
     */
    protected $MadelineProto;

    /**
     * @var array
     */
    protected $user;

    public function __construct($MadelineProto)
    {
        $this->MadelineProto = $MadelineProto;
        $this->getUser();
    }

    public function getUser()
    {
        if (!empty($this->user)) {
            return $this->user;
        }

        $this->user = $this->MadelineProto->get_self();
        return $this->user;
    }

    public function showUserName()
    {
        $username = $this->getUser()['first_name'] . " " . $this->getUser()['last_name'];
        echo "\e[0;31;42mLogged in as: $username \e[0m\n\n";
    }
}