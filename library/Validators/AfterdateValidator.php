<?php

namespace App\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;

class AfterdateValidator extends Validator implements ValidatorInterface
{
    /**
     * Executes the validation
     *
     * @param Phalcon\Validation $validator
     * @param string $attribute
     * @return boolean
     */
    public function validate(Validation $validator, $attribute)
    {
        $value = $validator->getValue($attribute);

        if ((strtotime($value) < time())) {

            $message = $this->getOption('message');
            if (!$message) {
                $message = 'Time too short';
            }

            $validator->appendMessage(new Message($message, $attribute, 'Afterdate'));

            return false;
        }

        return true;
    }
}