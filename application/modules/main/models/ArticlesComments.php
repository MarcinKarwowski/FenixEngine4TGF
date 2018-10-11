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
 * Class ArticlesComments
 * @package Main\Model
 */
class ArticlesComments extends Model
{

    public $id;
    public $articles_id;
    public $character_id;
    public $published;
    public $text;
    public $publishdate;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "articles_comments";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->useDynamicUpdate(true);
        $this->belongsTo("articles_id", "Main\Models\Articles", "id", array('alias' => 'Article'));
        $this->belongsTo("character_id", "Main\Models\Characters", "id", array('alias' => 'Author'));
    }
}
