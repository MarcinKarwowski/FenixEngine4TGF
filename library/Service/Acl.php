<?php

namespace App\Service;

use Phalcon\Db\RawValue;
use Phalcony\Validator\Exception;
use Main\Models\Users;
use Main\Models\RememberTokens;
use Main\Models\SuccessLogins;
use Main\Models\FailedLogins;

/**
 * Class Acl
 * @package App\Service
 */
class Acl extends \Phalcon\Mvc\User\Component
{
    /*
     * Check user access rights
     */
    public function checkAccess($accessPoint, $identity, $dispatcher)
    {
        if (!$identity) {
            if (!$this->request->isAjax()) {
                $this->flash->error($this -> translate['log-in']);
            }
            $this->view->disable();
            $this->response->redirect('');
            return false;
        }

        // Checked only ingame components
        if ($accessPoint == 'ingame')
        {
            if (!$identity['activeChar'] && $this -> config -> game -> params -> characterNeed == true && $dispatcher -> getControllerName() != 'charcreator')
            {
                $this->flash->notice($this -> translate['acl-create_first_char']);
                $this->view->disable();
                $this->response->redirect('/charcreator/lobby');
                return false;
            }
        }
        elseif ($accessPoint == 'superadmin')
        {
            // check permissions
            $permit = false;
            foreach ($identity['permissions'] as $perm)
            {
                if ($perm == '/admin')
                {
                    if ($this->router->getRewriteUri() == '/admin') $permit = true;
                }
                elseif ($perm == '/admin/game/panel')
                {
                    if ($this->router->getRewriteUri() == '/admin/game/panel') $permit = true;
                }
                elseif (strpos($this->router->getRewriteUri(), $perm) !== false) $permit = true;
            }
            if ($identity['group'] != 'Admin' && !$permit) {
                $this->flash->error($this->translate['acl-noaccess']);
                $this->view->disable();
                return $this->response->redirect('');
            }
        }
        return true;
    }

    /*
     * check user permiossion to action
     */
    public function checkPermit($permitname)
    {
        // admin can everything
        //if ($this->auth->getIdentity()['group'] == 'Admin') return true;
        // minions can nothing
        if (isset($this->auth->getIdentity()['permissions'][$permitname])) return true;
        return false;
    }
}
