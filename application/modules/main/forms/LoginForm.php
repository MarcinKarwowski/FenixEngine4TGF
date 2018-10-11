<?php
namespace Main\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;

class LoginForm extends Form
{

    public function initialize()
    {
        // Email
        $email = new Text('email', array(
            'placeholder' => 'Email'
        ));

        $email->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-email_required']
            )),
            new Email(array(
                'message' => $this -> translate['form-email_notvalid']
            ))
        ));

        $this->add($email);

        // Password
        $password = new Password('password', array(
            'placeholder' => 'Password'
        ));

        $password->addValidator(new PresenceOf(array(
            'message' => $this -> translate['form-pass_required']
        )));

        $this->add($password);

        // Remember
        $remember = new Check('remember', array(
            'value' => $this -> translate['form-yes']
        ));

        $remember->setLabel('Remember me');

        $this->add($remember);

        // CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getSessionToken(),
            'message' => $this -> translate['form-token_csrf']
        )));

        $this->add($csrf);

        $this->add(new Submit($this -> translate['form-login'], array(
            'class' => 'btn btn-success'
        )));
    }
}
