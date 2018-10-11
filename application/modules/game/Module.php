<?php
/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2015 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | Postapo RPG Game Module                                                            |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

namespace Game;

use Phalcon\DiInterface;
use Phalcon\Mvc\View;

class Module
{
    public function registerAutoloaders(DiInterface $dependencyInjector = null)
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array(
            'Game\Library' => APPLICATION_PATH . '/modules/game/library/',
            'Game\Controller' => APPLICATION_PATH . '/modules/game/controllers/',
            'Game\Models' => APPLICATION_PATH . '/modules/game/models/',
            'Game\Forms' => APPLICATION_PATH . '/modules/game/forms/',
            'Main\Models' => APPLICATION_PATH . '/modules/main/models/',
            'Admin\Controller' => APPLICATION_PATH . '/modules/admin/controllers/',
            'Game\Plugins' => APPLICATION_PATH . '/modules/admin/plugins/',
        ));
        $loader->register();
    }

    public function registerServices(DiInterface $di = null)
    {
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace('Game\Controller');

        /**
         * @var $view \Phalcon\Mvc\View
         */
        $view = $di->get('view');
        $view->setViewsDir(APPLICATION_PATH . '/modules/game/views/');

        //Disable several levels
        $view->disableLevel(array(
            View::LEVEL_LAYOUT      => true,
            View::LEVEL_MAIN_LAYOUT => true
        ));

        $di->set('view', $view);
    }
}
