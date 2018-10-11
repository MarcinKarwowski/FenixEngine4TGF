<?php

namespace Main\Models;

use Phalcon\Mvc\Model;

/**
 * Class Media
 * @package Main\Models
 */
class Media extends Model
{
    public $id;
    public $title;
    public $url;
    public $type;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "media";
    }

    /*
     * Initialization
     */
    public function initialize()
    {

    }

    public function getTrueType($type)
    {
        return in_array($type, ['IMG']);
    }
}
