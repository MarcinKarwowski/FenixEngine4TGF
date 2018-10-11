<?php
namespace Main\Models;

use Phalcon\Mvc\Model;

/**
 * EmailConfirmations
 * Stores the reset password codes and their evolution
 */
class EmailJobs extends Model
{

    public $id;

    public $users_id;

    public $code;

    public $createdAt;

    public $modifiedAt;

    public $confirmed;

    /*
 * Set source table
 */
    public function getSource()
    {
        return "email_jobs";
    }

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the confirmaton
        $this->createdAt = time();

        // Generate a random confirmation code
        $this->code = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(24)));

        // Set status to non-confirmed
        $this->confirmed = 0;
    }

    /**
     * Sets the timestamp before update the confirmation
     */
    public function beforeValidationOnUpdate()
    {
        // Timestamp the confirmaton
        $this->modifiedAt = time();
    }

    /**
     * Send a confirmation e-mail to the user after create the account
     */
    public function afterCreate()
    {
        $this->getDI()
            ->getMail()
            ->send(
                array(
                    $this->user->email => $this->user->name
                ),
                $this->getDI()['translate']['email-confirm'],
                'confirmation',
                array('confirmUrl' => '/confirm/' . $this->code . '/' . $this->user->email)
            );
    }

    public function initialize()
    {
        $this->belongsTo('users_id', 'Main\Models\Users', 'id', array(
            'alias' => 'user'
        ));
    }
}
