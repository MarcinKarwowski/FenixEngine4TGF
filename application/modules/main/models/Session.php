<?php

namespace Main\Models;

use Phalcon\Mvc\Model;

/**
 * Class Session
 * @package Main\Models
 */
class Session extends Model
{
    public $session_id;
    public $data;
    public $created_at;
    public $updated_at;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "session";
    }

    /*
     * Initialization
     */
    public function initialize()
    {

    }
}
