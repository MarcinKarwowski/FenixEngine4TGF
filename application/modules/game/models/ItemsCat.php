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
 * Class ItemsCat
 * @package Main\Model
 */
class ItemsCat extends Model
{

    public $id;
    public $name;
    public $text;
    public $type;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_items_cat";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->hasMany('id', 'Game\Models\Items', 'category_id', array('alias' => 'Items'));
    }
}
