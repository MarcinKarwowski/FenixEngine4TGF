<?php
/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2015 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | Admin Module                                                            |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

namespace Admin;

use Phalcon\DiInterface;
use Phalcon\Mvc\View;

class Module
{
    public function registerAutoloaders(DiInterface $dependencyInjector = null)
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array(
            'Admin\Controller' => APPLICATION_PATH . '/modules/admin/controllers/',
            'Admin\Models' => APPLICATION_PATH . '/modules/admin/models/',
            'Admin\Forms' => APPLICATION_PATH . '/modules/admin/forms/',
            'Game\Library' => APPLICATION_PATH . '/modules/game/library/',
            'Game\Models' => APPLICATION_PATH . '/modules/game/models/',
            // main models
            'Main\Models' => APPLICATION_PATH . '/modules/main/models/',
        ));
        $loader->register();
    }

    public function registerServices(DiInterface $di = null)
    {
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace('Admin\Controller');

        /**
         * @var $view \Phalcon\Mvc\View
         */
        $view = $di->get('view');
        $view->setViewsDir(APPLICATION_PATH . '/modules/admin/views/');

        //Disable several levels
        $view->disableLevel(array(
            View::LEVEL_LAYOUT      => true,
            View::LEVEL_MAIN_LAYOUT => true
        ));

        $di->set('view', $view);
    }
}
