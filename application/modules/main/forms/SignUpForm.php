<?php
namespace Main\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class SignUpForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        $name = new Text('name');

        $name->setLabel('Name');

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-name_required']
            ))
        ));

        $this->add($name);

        // Email
        $email = new Text('semail');

        $email->setLabel('E-Mail');

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
        $password = new Password('spassword');

        $password->setLabel('Password');

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

        $confirmPassword->setLabel('Confirm Password');

        $confirmPassword->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-pass_required_conf']
            ))
        ));

        $this->add($confirmPassword);

        // Remember
        $terms = new Check('terms', array(
            'value' => $this -> translate['form-yes']
        ));

        $terms->setLabel('Accept terms and conditions');

        $terms->addValidator(new Identical(array(
            'value' => $this -> translate['form-yes'],
            'message' => $this -> translate['form-require_accept_regulamin']
        )));

        $this->add($terms);

        // CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getToken(),
            'message' => $this -> translate['form-token_csrf']
        )));

        $this->add($csrf);

        // Sign Up
        $this->add(new Submit($this -> translate['form-register'], array(
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
