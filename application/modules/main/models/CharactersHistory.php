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
 * Class CharactersHistory
 * @package Main\Model
 */
class CharactersHistory extends Model
{

    public $id;
    public $character_id;
    public $place;
    public $title;
    public $text;
    public $date;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "characters_history";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->belongsTo("character_id", "Main\Models\Characters", "id", array('alias' => 'HistoryOwner'));
    }
}
