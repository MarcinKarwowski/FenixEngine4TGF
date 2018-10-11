<?php

namespace App\Service;

class Security extends \Phalcon\Security
{
    public function getTokenKey($numberBytes = 13)
    {
        $key = '$PHALCON/CSRF/KEY$';

        $tokenKey = $this-> getDI() -> getDefault()->get('session')->$key;

        if ($tokenKey)
        {
            return $tokenKey;
        }

        return parent::getTokenKey($numberBytes);
    }

    public function getToken($numberBytes = 32)
    {
        $key = '$PHALCON/CSRF$';

        $token = $this-> getDI() -> getDefault()->get('session')->$key;

        if ($token)
        {
            return $token;
        }

        return parent::getToken($numberBytes);
    }
}