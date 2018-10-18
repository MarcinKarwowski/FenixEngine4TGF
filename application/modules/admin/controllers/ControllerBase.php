<?php

namespace Admin\Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use App\Tmpl\AdminMenu;

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

        $strControllerName = $dispatcher->getControllerName();

        // send controller info to the view
        $this->view->controller = $strControllerName;
        // controller params
        $this->view->params = $this -> router -> getMatches() ? $this -> router -> getMatches() : [];

        // ACL check
        if ($this -> acl -> checkAccess('superadmin', $identity, $dispatcher) === false)
        {
            return true;
        }

        // admin menu
        if (is_file(APPLICATION_PATH.'/modules/game/adminMenu.php'))
        {
            AdminMenu::set(include APPLICATION_PATH.'/modules/game/adminMenu.php');
        }

        // set layout and partials
        $this->view->setLayoutsDir('../../../templates/layouts/');
        $this->view->setPartialsDir('');

        // Generate token one for page
        $this->view->csrfToken = $this->security->getToken();
        $this->view->auth = $identity;

        $userData = $this->auth -> getUser();
        $this->view->user = array('registerdate' => date('d-m-Y', $userData -> registerdate));
        $this->view->new_version = trim(file_get_contents('https://raw.githubusercontent.com/ThoranRion/FenixEngine4TGF/master/VERSION.md'));
        if (version_compare($this->view->new_version, $this->config->game->engineVer, '>')) {
            $this->view->showUpdate = true;
        }
    }

    /*
     * Add admin menu to panel
     */
    public function afterExecuteRoute()
    {
        // admin menu
        $this->view->adminMenu = AdminMenu::get();
    }
}
