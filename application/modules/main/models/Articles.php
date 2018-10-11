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
 * Class Articles
 * @package Main\Model
 */
class Articles extends Model
{

    public $id;
    public $type;
    public $title;
    public $published;
    public $publishdate;
    public $editdate;
    public $sortorder;
    public $views;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "articles";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->useDynamicUpdate(true);
        $this->hasOne("id", "Main\Models\ArticlesText", "articles_id", array('alias' => 'Textdata'));
    }

    public function getDate()
    {
        return date('d-m-Y H:i:s', $this -> publishdate);
    }
    public function getTrueType($type)
    {
        return in_array($type, ['NEWS']);
    }
}
