<?php

namespace Main\Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

/**
 * Class Users
 * @package Main\Models
 */
class Users extends Model
{
    public $id;
    public $date_created;
    public $date_modified;
    public $name;
    public $avatar;
    public $email;
    public $password;
    public $active_characters_id;
    public $template;
    public $active;
    public $lpv;
    public $registerdate;
    public $premiumdate;
    public $shopmoney;
    public $options;
    public $publish;
    public $deleted;
    public $group_id;

    /*
    * Set source table
    */
    public function getSource()
    {
        return "users";
    }

    /*
     * Initialization
     */
    public function initialize()
    {
        $this->belongsTo('group_id', 'Main\Models\Group', 'id', array('alias' => 'Group'));
        $this->hasMany('id', 'Main\Models\Permissions', 'users_id', array('alias' => 'Permissions'));
        $this->hasMany('id', 'Main\Models\Characters', 'users_id', array('alias' => 'Characters'));
        $this->hasOne('active_characters_id', 'Main\Models\Characters', 'id', array('alias' => 'Character'));

        $this->hasMany('id', 'Main\Models\ResetPasswords', 'usersId', array(
            'alias' => 'resetPasswords',
            'foreignKey' => array(
                'message' => $this->getDI()['translate']['acl-user_has_actions']
            )
        ));
    }

    /**
     * @param string $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->date_created = $dateCreated;
    }

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        if (empty($this->password)) {

            // Generate a plain temporary password
            $tempPassword = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(12)));

            // Use this password as default
            $this->password = $this->getDI()
                ->getSecurity()
                ->hash($tempPassword);
        }

        // The account must be confirmed via e-mail
        $this->active = 0;

        // The account reg date
        $this->registerdate = time();
    }


    /**
     * Send a confirmation e-mail to the user if the account is not active
     */
    public function afterCreate()
    {
        if ($this->active == 0) {

            $emailConfirmation = new EmailJobs();

            $emailConfirmation->users_id = $this->id;

            if ($emailConfirmation->save()) {
                $this->getDI()
                    ->getFlash()
                    ->notice($this->getDI()['translate']['email-sended_confirm'] . $this->email);
            }
        }
    }

    /**
     * Validate that emails are unique across users
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new Uniqueness(
                [
                    'message' => $this->getDI()['translate']['acl-email_already_taken'],
                ]
            )
        );

        $validator->add(
            'name',
            new Uniqueness(
                [
                    'message' => $this->getDI()['translate']['acl-name_already_taken'],
                ]
            )
        );

        return $this->validate($validator);
    }
}
