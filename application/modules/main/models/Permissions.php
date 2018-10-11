<?php

namespace Main\Models;

use Phalcon\Mvc\Model;

/**
 * Class Permissions
 * @package Main\Models
 */
class Permissions extends Model
{
    public $id;
    public $users_id;
    public $permission_name;
    public $permission_url;

    /*
     * Set source table
     */
    public function getSource()
    {
        return "users_permissions";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->belongsTo('users_id', 'Main\Models\Users', 'id', array('alias' => 'PermOwner'));
    }
}
