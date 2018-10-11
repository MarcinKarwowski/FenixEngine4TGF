<?php

namespace App\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;

class CreatemapValidator extends Validator implements ValidatorInterface
{
    public function validate(Validation $validator, $attribute)
    {
        $value = $validator->getValue($attribute);

        $mapdir = $this->getOption('mapdir');
        if ($value == 1 && is_dir($mapdir) && count(glob($mapdir. "*.jpg")) == 0) {
            $message = $this->getOption('message');
            if (!$message) {
                $message = 'No map uploaded';
            }

            $validator->appendMessage(new Message($message, $attribute, 'Createmap'));

            return false;
        }

        return true;
    }
}