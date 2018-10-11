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
 * Class WikipediaText
 * @package Main\Model
 */
class WikipediaText extends Model
{

    public $articles_id;
    public $text;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "wikipedia_text";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->useDynamicUpdate(true);
        $this->belongsTo("articles_id", "Main\Models\Wikipedia", "id", array('alias' => 'Wikiart'));
    }

    public function setText($text)
    {
        $this -> text = $text;

        return $this;
    }
}
