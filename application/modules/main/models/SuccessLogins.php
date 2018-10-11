<?php
namespace Main\Models;

use Phalcon\Mvc\Model;

/**
 * SuccessLogins
 * This model registers successfull logins registered users have made
 */
class SuccessLogins extends Model
{

    /**
     * @Id
     * @Identity
     * @GeneratedValue
     * @Primary
     * @Column(type="integer")
     * @var integer
     */
    public $id;

    /**
     * @Column(type="integer")
     * @var integer
     */
    public $user_id;

    /**
     * @Column(type="string")
     * @var string
     */
    public $ip;

    /**
     * @Column(type="string")
     * @var string
     */
    public $userAgent;

    /*
    * Set source table
    */
    public function getSource()
    {
        return "success_logins";
    }

    public function initialize()
    {
        $this->belongsTo('users_id', 'Main\Models\Users', 'id', array(
            'alias' => 'user'
        ));
    }
}
