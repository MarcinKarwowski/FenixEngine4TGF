<?php
namespace Main\Models;

use Phalcon\Mvc\Model;

/**
 * RememberTokens
 * Stores the remember me tokens
 */
class RememberTokens extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $users_id;

    /**
     *
     * @var string
     */
    public $token;

    /**
     *
     * @var string
     */
    public $userAgent;

    /**
     *
     * @var integer
     */
    public $created_at;

    /*
    * Set source table
    */
    public function getSource()
    {
        return "remember_tokens";
    }

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the confirmaton
        $this->created_at = time();
    }

    public function initialize()
    {
        $this->belongsTo('users_id', 'Main\Models\Users', 'id', array(
            'alias' => 'user'
        ));
    }
}
