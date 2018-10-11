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
 * Class ChatsMessages
 * @package Main\Model
 */
class ChatsMessages extends Model
{

    public $id;
    public $character_id;
    public $room_id;
    public $date;
    public $msg;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_chats_msg";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->belongsTo("character_id", "Main\Models\Characters", "id", array('alias' => 'Writer'));
        $this->belongsTo("room_id", "Main\Models\Chats", "id", array('alias' => 'MessagesRoom'));
    }
}
