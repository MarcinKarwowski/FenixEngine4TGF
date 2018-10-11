<?php
namespace Main\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\Email;

class ForgotPasswordForm extends Form
{

    public function initialize()
    {
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

        // CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getToken(),
            'message' => $this -> translate['form-token_csrf']
        )));

        $this->add($csrf);

        $this->add(new Submit($this -> translate['send'], array(
            'class' => 'btn btn-primary'
        )));
    }
}
