<?php

namespace App\Events;

use danog\MadelineProto\API;

class MadelineProtoHandler
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
        $this->MadelineProto = new API(config('telegram.sessions.path'), config('telegram', []));
        $this->MadelineProto->start();
    }

    protected function __clone()
    {
    }

    public function getMadelineProto()
    {
        return $this->MadelineProto;
    }


}