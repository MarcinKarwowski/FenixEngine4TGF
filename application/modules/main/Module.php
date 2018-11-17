<?php

/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2015 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | Main Module                                                            |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

namespace Main;

use Phalcon\Mvc\View;

class Module
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array(
            'Main\Controller' => APPLICATION_PATH . '/modules/main/controllers/',
            'Main\Models' => APPLICATION_PATH . '/modules/main/models/',
            'Main\Forms' => APPLICATION_PATH . '/modules/main/forms/',
            // game namsespace
            'Game\Controller' => APPLICATION_PATH . '/modules/game/controllers/',
            'Game\Library' => APPLICATION_PATH . '/modules/game/library/',
            'Game\Models' => APPLICATION_PATH . '/modules/game/models/',
        ));
        $loader->register();
    }

    public function registerServices($di)
    {
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace('Main\Controller');

        /**
         * @var $view \Phalcon\Mvc\View
         */
        $view = $di->get('view');

        //Disable several levels
        $view->disableLevel(array(
            View::LEVEL_LAYOUT      => true,
            View::LEVEL_MAIN_LAYOUT => true
        ));

        $di->set('view', $view);
    }
}
