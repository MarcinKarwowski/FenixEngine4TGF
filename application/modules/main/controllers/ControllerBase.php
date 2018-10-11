<?php

namespace Main\Controller;

use Main\Models\Users;
use Main\Models\Session;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Main\Forms\ForgotPasswordForm;
use Main\Forms\LoginForm;
use Main\Forms\SignUpForm;

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
        // Get the current identity
        $identity = $this->auth->getIdentity();
        $this->view->auth = $identity;

        $strControllerName = $dispatcher->getControllerName();
        $strActionName = $dispatcher->getActionName();

        if (is_file(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $this->config->game->template . DIRECTORY_SEPARATOR . strtolower($strControllerName) . DIRECTORY_SEPARATOR . strtolower($strActionName) . '.volt')) {
            $this->view->setViewsDir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $this->config->game->template);
            $this->view->setPartialsDir('');
            $this->view->setLayoutsDir(APPLICATION_PATH.'/templates/themes/' . $this->config->game->template.'/');
        } else {
            $this->view->setPartialsDir('');
            $this->view->setLayoutsDir(APPLICATION_PATH.'/templates/themes/' . $this->config->game->template.'/');
        }

        // set counter page if counter time specified
        if ((int)$this->config->game->startTime > time() && $identity === false && $strControllerName != 'session') $this->view->setTemplateAfter('counterpage');
        else $this->view->setTemplateAfter('mainpage');

        // Generate token one for page
        $this->view->csrfToken = $this->security->getToken();

        // Set forms
        $this->forms->set('login', new LoginForm());
        $this->forms->set('signup', new SignUpForm());
        $this->forms->set('forgotpassword', new ForgotPasswordForm());

        // Count logged in
        $this->view->accountCount = Users::count();
        $this->view->accountLogged = Users::find(["conditions" => "lpv > ".(time()-300)])->count();
    }
}
