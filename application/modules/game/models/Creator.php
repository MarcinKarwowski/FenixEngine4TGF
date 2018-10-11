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
 * Class Articles
 * @package Main\Model
 */
class Creator extends Model
{

    public $id;
    public $name;
    public $text;
    public $orderid;
    public $showinprofile;
    public $showincreator;
    public $type;
    public $params;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_cr_categories";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->hasMany("id", "Game\Models\Creations", "category_id", array('alias' => 'Options'));
    }
}
