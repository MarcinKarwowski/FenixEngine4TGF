<?php

/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2015 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

namespace Main\Models;

use Phalcon\Mvc\Model;

/**
 * Class CharactersNotifications
 * @package Main\Model
 */
class CharactersNotifications extends Model
{

    public $id;
    public $character_id;
    public $game_notifications_id;
    public $readed;
    public $expiry;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "characters_notifications";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->belongsTo("character_id", "Main\Models\Characters", "id", array('alias' => 'NotifyOwner'));
        $this->belongsTo("game_notifications_id", "Main\Models\Notifications", "id", array('alias' => 'NotifyText'));
    }

    public function setNotificationId($notification_id)
    {
        $this -> game_notifications_id = $notification_id;

        return $this;
    }

    public function setCharacterId($character_id)
    {
        $this -> character_id = $character_id;

        return $this;
    }

    public function setReaded($readed)
    {
        $this -> readed = $readed;

        return $this;
    }

    public function setExpiry($expiry)
    {
        $this -> expiry = $expiry;

        return $this;
    }
}
