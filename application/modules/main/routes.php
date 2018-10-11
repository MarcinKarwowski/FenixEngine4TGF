<?php

/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2015 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | Main module routes                                                     |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

$router->add('/session/signup', array(
    'module' => 'main',
    'controller' => 'session',
    'action' => 'signup'
));

$router->add('/session/login', array(
    'module' => 'main',
    'controller' => 'session',
    'action' => 'login'
));

$router->add('/session/logout', array(
    'module' => 'main',
    'controller' => 'session',
    'action' => 'logout'
));

$router->add('/session/forgot-password', array(
    'module' => 'main',
    'controller' => 'session',
    'action' => 'forgotPassword'
));
// Show news
$router->add('/news/:int', array(
    'module' => 'main',
    'controller' => 'index',
    'action' => 'show',
    'id' => 1
));

// enter into game
$router->add('/play', array(
    'module' => 'main',
    'controller' => 'index',
    'action' => 'play'
));

// game resets
$router->add('/reset/:int', array(
    'module' => 'main',
    'controller' => 'reset',
    'action' => 'index',
    'pass' => 1
));

// Email confirmation
$router->add('/confirm/{code}/{email}', array(
    'module' => 'main',
    'controller' => 'session',
    'action' => 'confirmEmail'
));
// Reset password
$router->add('/reset-password/{code}/{email}', array(
    'module' => 'main',
    'controller' => 'session',
    'action' => 'resetPassword'
));
// Engine update check
$router->add('/update', array(
    'module' => 'main',
    'controller' => 'update',
    'action' => 'index'
));
// Prepare update files
$router->add('/prepare-update/{code}', array(
    'module' => 'main',
    'controller' => 'update',
    'action' => 'prepareUpdate'
));
// Get update files
$router->add('/get-update/{code}', array(
    'module' => 'main',
    'controller' => 'update',
    'action' => 'getZip'
));
