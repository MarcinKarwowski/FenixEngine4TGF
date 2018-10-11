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
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class NeutralCharacters
 * @package Main\Models
 */
class NeutralCharacters extends Model
{

    public $id;
    public $owner_id;
    public $name;
    public $gender;
    public $avatar;
    public $profile;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "game_npcs";
    }

    /*
     * Initialization
     */
    public function initialize()
    {

    }

    /**
     * Validate
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'name',
            new PresenceOf(
                [
                    'message' => $this->getDI()['translate']['acl-name_already_taken'],
                ]
            )
        );

        return $this->validate($validator);
    }
}
