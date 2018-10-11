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
 * Class Items
 * @package Main\Model
 */
class Items extends Model
{

    public $id;
    public $category_id;
    public $name;
    public $text;
    public $price;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_items";
    }

    /*
     * Initialization
     */
    public function initialize()
    {

    }
}
