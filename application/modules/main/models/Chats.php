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
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class Chats
 * @package Main\Models
 */
class Chats extends Model
{

    public $id;
    public $showinn;
    public $owner_id;
    public $title;
    public $desc;
    public $days;
    public $hide;
    public $archived;
    public $last_msg_id;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_chats";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->hasMany('id', 'Main\Models\ChatsMessages', 'room_id', array('alias' => 'RoomMessages'));
        $this->hasOne('last_msg_id', 'Main\Models\ChatsMessages', 'id', array('alias' => 'LastMsg'));
        $this->hasOne('owner_id', 'Main\Models\Users', 'id', array('alias' => 'Owner'));
        $this->hasManyToMany(
            "id",
            "Main\Models\ChatsCharacters",
            "room_id", "character_id",
            "Main\Models\Characters",
            "id", array('alias' => 'RoomCharacters')
        );
    }

    /**
     * Validate
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'title',
            new PresenceOf(
                [
                    'message' => $this->getDI()['translate']['chat-name_already_taken'],
                ]
            )
        );

        return $this->validate($validator);
    }
}
