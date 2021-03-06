<?php

namespace App\Events;

use danog\MadelineProto\API;

class UserHandler
{
    /**
     * @var UserHandler
     */
    protected static $_instance = null;

    /**
     * @var API
     */
    protected $MadelineProto;

    /**
     * @var array
     */
    protected $user;

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function __construct()
    {
        $this->MadelineProto = MadelineProtoHandler::getInstance()->getMadelineProto();
        $this->getUser();
    }

    protected function __clone()
    {
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
        $username = $this->getUserName();
        echo "\e[0;31;42mLogged in as: $username \e[0m\n\n";
    }

    public function getUserName()
    {
        return $this->getUser()['first_name'] . (!empty($this->getUser()['last_name']) ? $this->getUser()['last_name'] : '');
    }

    public function getChatDetailLength()
    {
        return strlen($this->getUserName());
    }
}