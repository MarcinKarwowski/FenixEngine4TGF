<?php

namespace Main\Models;

use Phalcon\Mvc\Model;

/**
 * Class Group
 * @package Main\Models
 */
class Group extends Model
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
     * @Column(name="title", type="string")
     * @var string
     */
    public $title;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "users_groups";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->hasOne('id', 'Main\Models\Users', 'group_id', array('alias' => 'Users'));
    }
}
