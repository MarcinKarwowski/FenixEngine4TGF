<?php

namespace Admin\Controller;

use Main\Models\Permissions;
use Main\Models\Users;
use App\Tmpl\AdminMenu;

class PermissionsController extends ControllerBase
{
    /*
     * Custom perrmissions
     */
    public $permissions = [
        'chat_npc' => 1,
        'chat_delpost' => 1,
        'profile_edit' => 1,
    ];
    /**
     * Dashboard
     */
    public function indexAction()
    {
        // template
        $this -> view -> pageHeader = $this -> translate['perm-text'];
        $this -> view -> pageDesc = $this -> translate['perm-text_desc'];

        // get all permissions
        $this -> view -> permissions = $this->modelsManager->executeQuery("SELECT p.users_id, ANY_VALUE(p.id), u.name, u.email, u.id as uid FROM Main\Models\Permissions AS p LEFT JOIN Main\Models\Users AS u ON u.id=p.users_id GROUP BY p.users_id");
        $this -> view -> cpermissions = $this -> permissions;
    }

    public function editAction($params = 0)
    {
        $this->view->modalTitle = $this->translate[ 'perm-edit' ];

        $user = Users::findFirstById($params);
        $arrPerms = [];
        if ($user)
        {
            $perms = $this->modelsManager->executeQuery("SELECT * FROM Main\Models\Permissions WHERE users_id=?0 ORDER BY id", [$user -> id]);
            foreach ($perms as $perm)
            {
                $arrPerms[$perm -> permission_name] = $perm -> permission_url;
            }
            $this->view->obj -> perms = $arrPerms;
        }
        $this->view->obj = ['id' => $params, 'perms' => $arrPerms];
    }

    public function saveAction($params = null)
    {
        if ($this->request->isPost())
        {
            // find user
            $user = Users::findFirstById($this -> request -> getPost('users_id', 'int'));
            if (isset($user -> id))
            {
                // setted perms
                $arrPerms = [];
                foreach ($this -> request -> getPost('permissions') as $perm)
                {
                    $arrPerms[$perm] = 1;
                }

                // curent perms
                $arrCPerms = [];
                $perms = $this->modelsManager->executeQuery("SELECT * FROM Main\Models\Permissions WHERE users_id=?0 ORDER BY id", [$user -> id]);
                foreach ($perms as $perm)
                {
                    if ($perm -> permission_name == 'menu-dashboard') continue; // skip admin dashboard
                    $arrCPerms[$perm -> permission_name] = $perm -> id;
                }
                // reasign permissions
                foreach (AdminMenu::get() as $item)
                {
                    if (is_array($item))
                    {
                        foreach ($item as $index => $menu)
                        {
                            if ($index == 'menu-dashboard') continue; // skip admin dashboard
                            if (isset($arrCPerms[$index]) && !isset($arrPerms[$index]))
                            {
                                $this->modelsManager->executeQuery('DELETE FROM Main\Models\Permissions WHERE id=?0', [$arrCPerms[$index]]);
                                unset($arrCPerms[$index]);
                            }
                            elseif (!isset($arrCPerms[$index]) && isset($arrPerms[$index]))
                            {
                                $perm = new Permissions();
                                $perm -> users_id = $user -> id;
                                $perm -> permission_name = $index;
                                $perm -> permission_url = $menu['link'];
                                $perm -> save();
                                $arrCPerms[$index] = $perm -> id;
                            }
                        }
                    }
                }
                // add index item
                if (count($arrCPerms) > 0)
                {
                    $perm = new Permissions();
                    $perm -> users_id = $user -> id;
                    $perm -> permission_name = 'menu-dashboard';
                    $perm -> permission_url = '/admin';
                    $perm -> save();
                }
                else
                {
                    $this->modelsManager->executeQuery('DELETE FROM Main\Models\Permissions WHERE users_id=?0 AND permission_url=?1', [$user -> id, '/admin']);
                }
                foreach ($this -> permissions as $index => $item)
                {
                    if (isset($arrCPerms[$index]) && !isset($arrPerms[$index]))
                    {
                        $this->modelsManager->executeQuery('DELETE FROM Main\Models\Permissions WHERE id=?0', [$arrCPerms[$index]]);
                    }
                    elseif (!isset($arrCPerms[$index]) && isset($arrPerms[$index]))
                    {
                        $perm = new Permissions();
                        $perm -> users_id = $user -> id;
                        $perm -> permission_name = $index;
                        $perm -> permission_url = '/';
                        $perm -> save();
                    }

                }
                $this->flash->success($this->translate[ 'perm-saved' ]);
            }
            else
            {
                $this->flash->error($this->translate[ 'acl-user_dontexist' ]);
            }
            return $this->response->redirect('admin/permissions');
        }

        $this -> view -> pageHeader = $this -> translate['perm-text'];
        $this -> view -> pageDesc = $this -> translate['perm-text_desc'];
    }

    public function deleteAction($params = null)
    {
        $permissions = Permissions::find(["users_id = ?0", "bind" => [$params]]);

        $this->flash->error($this->translate[ 'perm-deleted' ]);
        $permissions -> delete();

        return $this->response->redirect('admin/permissions');
    }
}
