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
 * Class Locations
 * @package Main\Model
 */
class Locations extends Model
{

    public $id;
    public $parent_id;
    public $name;
    public $text;
    public $type;
    public $coords;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_locations";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->hasManyToMany(
            "id",
            "Game\Models\LocationsItems",
            "location_id", "item_id",
            "Game\Models\Items",
            "id", array('alias' => 'Buy')
        );
    }
}
