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
 * Class Messages
 * @package Main\Models
 */
class Messages extends Model
{

    public $id;
    public $character_id;
    public $sender_id;
    public $date;
    public $topic;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_messages";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->belongsTo('character_id', 'Main\Models\Characters', 'id', array('alias' => 'Owner'));
        $this->belongsTo('sender_id', 'Main\Models\Characters', 'id', array('alias' => 'Sender'));

        $this->hasOne("id", "Main\Models\MessagesText", "message_id", array('alias' => 'MyMessageText'));
    }

    /**
     * Validate
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'character_id',
            new PresenceOf(
                [
                    'message' => $this->getDI()['translate']['messages-receiver_desc'],
                ]
            )
        );

        $validator->add(
            'topic',
            new PresenceOf(
                [
                    'message' => $this->getDI()['translate']['messages-topic_desc'],
                ]
            )
        );

        return $this->validate($validator);
    }
}
