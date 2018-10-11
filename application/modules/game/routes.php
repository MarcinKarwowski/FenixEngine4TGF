<?php
/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2015 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | Admin module routes                                                     |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

// news
$router->add('/game/news/:action/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'news',
    'action' => 1,
    'digit' => 2
));

$router->add('/game/news/:action/:int/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'news',
    'action' => 1,
    'digit' => 2,
    'page' => '1'
));

$router->add('/game/news/:action', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'news',
    'action' => 1
));

$router->add('/game/news', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'news',
    'action' => 'index'
));

// wikipedia
$router->add('/wikipedia', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'wikipedia',
    'action' => 'index'
));
$router->add('/wikipedia/artykul/:params', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'wikipedia',
    'action' => 'article',
    'params' => 1
));

// chat
$router->add('/game/chat/index/:int/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'chat',
    'action' => 'index',
    'id' => 1,
    'page' => 2
));

$router->add('/game/chat/:action/:int/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'chat',
    'action' => 1,
    'digit' => 2,
    'type' => 3
));

$router->add('/game/chat/:action/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'chat',
    'action' => 1,
    'digit' => 2
));

$router->add('/game/chat/:action', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'chat',
    'action' => 1
));

$router->add('/game/chat', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'chat',
    'action' => 'index'
));

// account
$router->add('/game/account/:action', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'account',
    'action' => 1
));

$router->add('/game/notifications/:action', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'notify',
    'action' => 1
));

$router->add('/game/notifications/:action/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'notify',
    'action' => 1,
    'digit' => 2
));

$router->add('/game/notifications/index/{type}/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'notify',
    'digit' => 1
));

$router->add('/game/messages/:action', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'messages',
    'action' => 1
));

$router->add('/game/messages/:action/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'messages',
    'action' => 1,
    'digit' => 2
));

$router->add('/game/messages/read/:int/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'messages',
    'action' => 'read',
    'digit' => 1,
    'page' => 2
));

$router->add('/game/messages/delete/:int/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'messages',
    'action' => 'delete',
    'digit' => 1,
    'type' => 2
));

$router->add('/game/messages/save/:int/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'messages',
    'action' => 'save',
    'digit' => 1,
    'type' => 2
));

$router->add('/game/refresh/:action', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'refresh',
    'action' => 1
));

$router->add('/logs/:action', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'notify',
    'action' => 1
));

$router->add('/charcreator/:action', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'charcreator',
    'action' => 1
));

$router->add('/charcreator/begin/([0-9]{1})', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'charcreator',
    'action' => 'begin',
    'step' => 1
));

$router->add('/charcreator/next/([0-9]{1})', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'charcreator',
    'action' => 'next',
    'step' => 1
));

$router->add('/charcreator/chose/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'charcreator',
    'action' => 'chose',
    'charID' => 1
));

$router->add('/charcreator/edit/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'charcreator',
    'action' => 'edit',
    'charID' => 1
));

$router->add('/charcreator/delete/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'charcreator',
    'action' => 'delete',
    'charID' => 1
));

/* profile */
$router->add('/game/profile/extend/([0-9]{11})/([0-9]{11})', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'profile',
    'action' => 'extend',
    'type' => 1,
    'charId' => 2
));
$router->add('/game/profile/seek/([0-9]{11})/([0-9]{11})', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'profile',
    'action' => 'next',
    'current' => 1,
    'side' => 2
));

/* locations */
$router->add('/game/location/show/:int', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'location',
    'action' => 'show',
    'locID' => 1
));

$router->add('/game/location/coords', array(
    'namespace' => 'Game\Controller',
    'module' => 'game',
    'controller' => 'location',
    'action' => 'coords'
));
