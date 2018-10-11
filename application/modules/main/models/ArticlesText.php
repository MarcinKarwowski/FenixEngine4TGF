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
 * Class ArticlesText
 * @package Main\Model
 */
class ArticlesText extends Model
{

    public $articles_id;
    public $text;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "articles_text";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->useDynamicUpdate(true);
        $this->belongsTo("articles_id", "Main\Models\Articles", "id", array('alias' => 'Article'));
    }

    public function setText($text)
    {
        $this -> text = $text;

        return $this;
    }
}
