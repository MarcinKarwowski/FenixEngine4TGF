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
 * Class ChatsCharacters
 * @package Main\Model
 */
class ChatsCharacters extends Model
{

    public $id;
    public $character_id;
    public $room_id;
    public $modified_at;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_chats_chars";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->useDynamicUpdate(true);
        $this->belongsTo("character_id", "Main\Models\Characters", "id", array('alias' => 'Chaters'));
        $this->belongsTo("room_id", "Game\Models\Chats", "id", array('alias' => 'Rooms'));
    }
}
