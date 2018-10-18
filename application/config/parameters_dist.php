<?php

return array(
    'game' => [
        'engineVer' => '1.0',
        'title' => 'Fenix Engine',
        'description' => 'Silnik umożliwiający stworzenie przeglądarkowej gry MMO',
        'keywords' => 'TGF, MMORPG, rpg, fantasy, programowanie, silnik',
        'publicUrl' => 'e-fenix.info',
        'baseUri' => 'http://localhost/',
        'registerOff' => true,
        'GAIdentificator' => '',
        'template' => 'default',
        'template_text_color' => null,
        'params' =>
            array(
                'charactersAmount' => 3,
                'characterNeed' => true,
                'defaultPage' => 'game/chat',
                'levelCap' => 10,
                'levelOff' => false,
                'eraData' => '',
                'saveDate' => 1489278349,
                'eraDate' => 3516,
                'mapOn' => 0,
            ),
        'startTime' => 1293836400,
        'custom' => ' ',
    ],
    'modules' =>
        array(
            'messages' =>
                array(
                    'inboxlimit' => 2000,
                ),
        ),
    'mail' => [
        'fromName' => 'Fenix Engine',
        'fromEmail' => 'admin@e-fenix.info',
        'serverType' => 'sendmail',
        'smtp' => [
            'server' => 'mail.e-fenix.info',
            'port' => 25,
            'security' => '',
            'username' => 'admin@e-fenix.info',
            'password' => '8NDGK6cL'
        ]
    ],
    'db' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'fenix',
    ],
    'url' => array(
        'baseUri' => '/',
        'staticBaseUri' => '/assets/' //Change to CDN if needed
    ),
);
