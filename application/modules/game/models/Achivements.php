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

namespace Game\Models;

use Phalcon\Mvc\Model;

/**
 * Class Achivements
 * @package Main\Model
 */
class Achivements extends Model
{

    public $id;
    public $character_id;
    public $gain;
    public $type;
    public $text;
    public $date;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_characters_achivements";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->belongsTo('character_id', 'Main\Models\Characters', 'id', array('alias' => 'Owner'));
    }
}
