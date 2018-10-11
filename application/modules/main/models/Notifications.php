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
 * Class Notifications
 * @package Main\Models
 */
class Notifications extends Model
{

    public $id;
    public $title;
    public $text;
    public $type;
    public $item_id;
    public $popup;
    public $globals;
    public $date;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_notifications";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->hasMany("id", "Main\Models\CharactersNotifications", "game_notifications_id", array('alias' => 'Notifyinfo'));
    }

    public function beforeValidationOnCreate()
    {
        $this -> date = date('Y-m-d H:i:s');
    }

    public function afterCreate()
    {

    }
}
