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
class Creations extends Model
{

    public $id;
    public $name;
    public $category_id;
    public $wiki_id;
    public $text;
    public $params;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_cr_pages";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->belongsTo("category_id", "Game\Models\Creator", "id", array('alias' => 'Creator'));
        $this->hasMany("id", "Game\Models\Relations", "page_id", array('alias' => 'Links'));
        $this->belongsTo("wiki_id", "Main\Models\Wikipedia", "id", array('alias' => 'Wiki'));
    }
}
