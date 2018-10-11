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
 * Class Players
 * @package Main\Model
 */
class Players extends Model
{

    public $id;
    public $character_id;
    public $page_id;
    public $value;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_cr_players";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->belongsTo("character_id", "Main\Models\Characters", "id", array('alias' => 'Player'));
        $this->belongsTo("page_id", "Game\Models\Creations", "id", array('alias' => 'Page'));
    }
}
