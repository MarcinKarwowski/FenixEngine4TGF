<?php

namespace Admin\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Main\Models\Characters;

class EditCharacterForm extends Form
{

    public function initialize(Characters $characters)
    {
        $charName = new Text('name');
        $charName->addValidators(array(
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
        $this->add($charName);

        $charEq = new TextArea('equipment');
        $this->add($charEq);

        $charSpells = new TextArea('spells');
        $this->add($charSpells);

        $charEvents = new TextArea('events');
        $this->add($charEvents);

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
