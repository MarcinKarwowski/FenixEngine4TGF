<?php
/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2015 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

namespace Main\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Identical;

class ChangePasswordForm extends Form
{

    public function initialize()
    {
        // Password
        $password = new Password('password');

        $password->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-pass_required']
            )),
            new StringLength(array(
                'min' => 8,
                'messageMinimum' => $this -> translate['form-pass_required_short']
            )),
            new Confirmation(array(
                'message' => $this -> translate['form-pass_required_notmatch'],
                'with' => 'confirmPassword'
            ))
        ));

        $this->add($password);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword');

        $confirmPassword->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-pass_required_conf']
            ))
        ));

        $this->add($confirmPassword);

        // CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getToken(),
            'message' => $this -> translate['form-token_csrf']
        )));

        $this->add($csrf);

        $this->add(new Submit($this -> translate['save'], array(
            'class' => 'btn btn-primary'
        )));
    }
}
