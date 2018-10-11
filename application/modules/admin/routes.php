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

//All the routes start with /admin
$router->add('/admin/:params', array(
    'module' => 'admin',
    'controller' => 'index',
    'action' => 'index',
    'params' => 1
))->setName('admin');

$router->add('/admin/:controller/:params', array(
    'module' => 'admin',
    'controller' => 1,
    'action' => 'index',
    'params' => 2
));

$router->add('/admin/:controller/:action/:params', array(
    'module' => 'admin',
    'controller' => 1,
    'action' => 2,
    'params' => 3
));

$router->add('/admin/chose-game/{folder}', array(
    'module' => 'admin',
    'controller' => 'index',
    'action' => 'choseGame'
));

$router->add('/admin/info', array(
    'module' => 'admin',
    'controller' => 'index',
    'action' => 'info'
));

$router->add('/admin/check-update', array(
    'module' => 'admin',
    'controller' => 'index',
    'action' => 'checkUpdate'
));

$router->add('/admin/run-update', array(
    'module' => 'admin',
    'controller' => 'index',
    'action' => 'runUpdate'
));

$router->add('/admin/configure', array(
    'module' => 'admin',
    'controller' => 'index',
    'action' => 'configure'
));

// Game controllers
$router->add('/admin/game/:params', array(
    'module' => 'admin',
    'controller' => 'index',
    'action' => 'index',
    'params' => 1
))->setName('admin');

$router->add('/admin/game/:controller/:params', array(
    'module' => 'admin',
    'controller' => 1,
    'action' => 'index',
    'params' => 2
));

$router->add('/admin/game/:controller/:action/:params', array(
    'module' => 'admin',
    'controller' => 1,
    'action' => 2,
    'params' => 3
));

$router->add('/admin/game/:controller/:action/:params/:param', array(
    'module' => 'admin',
    'controller' => 1,
    'action' => 2,
    'params' => 3,
    'param' => 4
));
