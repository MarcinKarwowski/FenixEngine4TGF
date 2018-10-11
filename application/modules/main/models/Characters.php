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
 * Class Characters
 * @package Main\Models
 */
class Characters extends Model
{

    public $id;
    public $users_id;
    public $active;
    public $name;
    public $gender;
    public $avatar;
    public $level;
    public $pd;
    public $pu;
    public $pc;
    public $hp;
    public $max_hp;
    public $gold;
    public $location_id;
    public $ego_data;
    public $newlogs;
    public $newmsg;
    public $status;
    public $equipment;
    public $spells;
    public $events;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "characters";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->belongsTo('users_id', 'Main\Models\Users', 'id', array('alias' => 'Users'));
        $this->hasMany('id', 'Main\Models\Messages', 'character_id', array('alias' => 'MyMessages'));
        $this->hasMany('id', 'Main\Models\Messages', 'sender_id', array('alias' => 'SendMessages'));
        $this->hasMany('id', 'Main\Models\CharactersHistory', 'character_id', array('alias' => 'History'));

        $this->hasManyToMany(
            "id",
            "Main\Models\CharactersNotifications",
            "character_id", "game_notifications_id",
            "Main\Models\Notifications",
            "id", array('alias' => 'MyNotifications')
        );
    }

    /*
    * Get avatar
    */
    public function getAvatar()
    {
        if (is_file(PUBLIC_PATH.$this -> getDI()['config'] -> url -> staticBaseUri.'static/avatars/'.$this -> avatar))
        {
            return $this -> getDI()['config'] -> url -> staticBaseUri.'static/avatars/'.$this -> avatar;
        }
        else
        {
            return $this -> getDI()['config'] -> url -> staticBaseUri.'images/defaultav.jpg';
        }
    }
}
