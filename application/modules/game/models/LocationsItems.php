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
 * Class LocationsItems
 * @package Main\Model
 */
class LocationsItems extends Model
{

    public $id;
    public $location_id;
    public $item_id;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_locations_items";
    }

    /*
     * Initialization
     */
    public function initialize()
    {

    }
}
