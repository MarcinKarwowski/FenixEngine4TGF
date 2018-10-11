<?php

namespace Admin\Controller;

use Main\Models\Users;

class UsersController extends ControllerBase
{
    /**
     * Dashboard
     */
    public function indexAction()
    {
        // template
        $this -> view -> pageHeader = $this -> translate['users-list'];
        $this -> view -> pageDesc = '';

    }

    public function deleteAction($params = null)
    {
        $user = Users::findFirst(["id = ?0", "bind" => [$params]]);
        if (isset($user -> id) && $user -> id != 1 && $user -> id != 2)
        {
            foreach ($user->characters as $char)
            {
                $char -> update(['users_id' => 0]);
            }
            $this->flash->error($this->translate[ 'users-deleted' ]);
            $user -> delete();
        }

        return $this->response->redirect('admin/users');
    }

    public function deactivateAction($params = null)
    {
        $user = Users::findFirst(["id = ?0", "bind" => [$params]]);
        if (isset($user -> id) && $user -> id != 1 && $user -> id != 2)
        {
            if ($user -> active == 0) $user -> update(['active' => 1]);
            else $user -> update(['active' => 0]);
        }

        return $this->response->redirect('admin/users');
    }
}
