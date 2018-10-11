<?php
namespace Main\Models;

use Phalcon\Mvc\Model;

/**
 * FailedLogins
 * This model registers unsuccessfull logins registered and non-registered users have made
 */
class FailedLogins extends Model
{

    /**
     * @Column(type="integer")
     * @var integer
     */
    public $id;

    /**
     * @Column(type="integer")
     * @var integer
     */
    public $users_id;

    /**
     * @Column(type="string")
     * @var string
     */
    public $ip;

    /**
     * @Column(type="integer")
     * @var integer
     */
    public $attempted;

    /*
    * Set source table
    */
    public function getSource()
    {
        return "failed_logins";
    }

    public function initialize()
    {
        $this->belongsTo('users_id', 'Main\Models\Users', 'id', array(
            'alias' => 'user'
        ));
    }
}
