<?php

namespace Install\Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Install\Forms\InstallForm;

/**
 * ControllerBase
 * This is the base controller for all controllers in the application
 */
class ControllerBase extends Controller
{

    /**
     * Execute before the router so we can determine if this is a private controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @param Dispatcher $dispatcher
     * @return boolean
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $this->view->setViewsDir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'install');
        $this->view->setPartialsDir('../../partials/');
        $this->view->setLayoutsDir('/');

        // Set forms
        $this->forms->set('install', new InstallForm());
    }
}
