<?php

namespace Game\Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Game\Library\everyPage;

/**
 * ControllerBase
 * This is the base controller for all controllers in the application
 */
class ControllerBase extends Controller
{
    public $identity;

    /**
     * Execute before the router so we can determine if this is a private controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @param Dispatcher $dispatcher
     * @return boolean
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        // Get the current identity
        $this->identity = $this->auth->getIdentity();

        // ACL check
        if ($this->acl->checkAccess('ingame', $this->identity, $dispatcher) === false) return false;

        $strControllerName = $dispatcher->getControllerName();
        $strActionName = $dispatcher->getActionName();

        // Generate token one for page
        $this->view->csrfToken = $this->security->getToken();
        // send auth info to the view
        $this->view->auth = $this->identity;
        // send controller info to the view
        $this->view->controller = $strControllerName;
        // controller params
        $this->view->params = $this -> router -> getMatches() ? $this -> router -> getMatches() : [];

        // We can override templates. Only ingame and game
        if (is_file(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $this->config->game->template . DIRECTORY_SEPARATOR . strtolower($strControllerName) . DIRECTORY_SEPARATOR . strtolower($strActionName) . '.volt')) {
            $this->view->setViewsDir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $this->config->game->template);
        } else {
            $this->view->setViewsDir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'views');
        }
        $this->view->setPartialsDir('');
        $this->view->setLayoutsDir(APPLICATION_PATH.'/templates/views/_layouts/');
        /*
         * set user defined layout
         */
        $this->view->setTemplateAfter('default');

        // set default variables
        new everyPage($dispatcher);
    }
}
