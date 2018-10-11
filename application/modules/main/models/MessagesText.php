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
 * Class MessagesText
 * @package Main\Models
 */
class MessagesText extends Model
{

    public $message_id;
    public $text;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_messages_desc";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->belongsTo('message_id', 'Main\Models\Messages', 'id', array('alias' => 'MyMessageData'));
    }
}
