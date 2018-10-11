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
 * Class ItemsPlayers
 * @package Main\Model
 */
class ItemsPlayers extends Model
{

    public $id;
    public $item_id;
    public $character_id;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_items_players";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->hasOne('item_id', 'Game\Models\Items', 'id', array('alias' => 'Item'));
    }
}
