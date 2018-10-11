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
class Relations extends Model
{

    public $id;
    public $page_id;
    public $link_page_id;
    public $value;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_cr_relations";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->belongsTo("page_id", "Game\Models\Creations", "id", array('alias' => 'Hasit'));
        $this->belongsTo("link_page_id", "Game\Models\Creations", "id", array('alias' => 'Needit'));
    }
}
