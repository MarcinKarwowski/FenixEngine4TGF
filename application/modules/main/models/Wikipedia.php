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

namespace Main\Models;

use Phalcon\Mvc\Model;

/**
 * Class Wikipedia
 * @package Main\Model
 */
class Wikipedia extends Model
{

    public $id;
    public $title;
    public $published;
    public $publishdate;
    public $editdate;
    public $sortorder;
    public $views;

    public $parent_id;
    public $orderid;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "wikipedia";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->useDynamicUpdate(true);
        $this->hasOne("id", "Main\Models\WikipediaText", "articles_id", array('alias' => 'Wikitext'));
    }

    public function getDate()
    {
        return date('d-m-Y H:i:s', $this -> publishdate);
    }
}
