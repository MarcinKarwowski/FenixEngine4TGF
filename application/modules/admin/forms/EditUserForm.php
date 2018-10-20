<?php

namespace Admin\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Uniqueness;
use Main\Models\Users;

class EditUserForm extends Form
{

    public function initialize(Users $user)
    {
        $usersName = new Text('name');
        $usersName->addValidators(array(
            new PresenceOf(
                [
                    'message' => $this->translate['form-field_required']
                ]
            ),
            new StringLength(
                [
                    'max' => 50,
                    'min' => 3,
                    'messageMaximum' => $this->translate['form-field_toolong'],
                    'messageMinimum' => $this->translate['form-field_tooschort']
                ]
            ),
        ));
        $this->add($usersName);

        $userGroup = new Select("group_id", array(1 => $this->translate['users-group_user'], 2 => $this->translate['users-group_admin']), array(
            'using' => array('id', 'name'),
        ));
        $userGroup->addValidators(array(
            new PresenceOf(
                [
                    'message' => $this->translate['form-field_required']
                ]
            )
        ));
        $this->add($userGroup);

        $userEmail = new Text('email');
        $userEmail->addValidators(array(
            new PresenceOf(
                [
                    'message' => $this->translate['form-field_required']
                ]
            ),
            new Email(
                [
                    'message' => $this->translate['form-field_email_not_valid'],
                ]
            ),
            new Uniqueness(
                [
                    'message' => $this->translate['acl-email_already_taken'],
                    'model' => $user
                ]
            )
        ));
        $this->add($userEmail);

        $userActive = new Select("active", [1 => $this->translate['yes'], 0 => $this->translate['no']], [
            'using' => array('id', 'name'),
        ]);
        $userActive->addValidators(array(
            new PresenceOf(
                [
                    'message' => $this->translate['form-field_required']
                ]
            ),
        ));
        $this->add($userActive);

        // CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getToken(),
            'message' => $this->translate['form-token_csrf']
        )));

        $this->add($csrf);

        // Sign Up
        $this->add(new Submit($this->translate['save'], array(
            'class' => 'btn btn-success'
        )));
    }

    /**
     * Prints messages for a specific element
     */
    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
