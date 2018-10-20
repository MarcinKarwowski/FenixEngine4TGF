<?php

namespace Admin\Controller;

use Admin\Forms\EditCharacterForm;
use Admin\Forms\EditUserForm;
use Main\Models\Users;
use Main\Models\Characters;
use App\Service\ParseString;

class UsersController extends ControllerBase
{
    /**
     * Dashboard
     */
    public function indexAction()
    {
        // template
        $this->view->pageHeader = $this->translate['users-list'];
        $this->view->pageDesc = '';

    }

    public function deleteAction($params = null)
    {
        $user = Users::findFirst(["id = ?0", "bind" => [$params]]);
        if (isset($user->id) && $user->id != 1 && $user->id != 2) {
            foreach ($user->characters as $char) {
                $char->update(['users_id' => 0]);
            }
            $this->flash->error($this->translate['users-deleted']);
            $user->delete();
        }

        return $this->response->redirect('admin/users');
    }

    public function deactivateAction($params = null)
    {
        $user = Users::findFirst(["id = ?0", "bind" => [$params]]);
        if (isset($user->id) && $user->id != 1 && $user->id != 2) {
            if ($user->active == 0) $user->update(['active' => 1]);
            else $user->update(['active' => 0]);
        }

        return $this->response->redirect('admin/users');
    }

    public function showAction($params = null)
    {
        // template
        $this->view->pageHeader = $this->translate['users-view'];
        $this->view->pageDesc = '';

        $user = Users::findFirst(["id = ?0", "bind" => [$params]]);
        if (!isset($user->id)) {
            $this->flash->error($this->translate['users-no_exist']);
            $this->view->disable();

            return $this->response->redirect('admin/users');
        }

        $this->view->userObj = $user;
        $form = new EditUserForm($user);
        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $user->name = $this->request->getPost('name');
                $user->email = $this->request->getPost('email');
                $user->group_id = (int)$this->request->getPost('group_id');
                $user->active = (int)$this->request->getPost('active');
                if ($user->save()) {
                    $this->flash->success($this->translate['users-save_success']);
                } else $this->flash->error($this->translate['users-save_error']);

                $this->view->disable();

                return $this->response->redirect('/admin/users/show/'.$user->id);
            }
        }
        $this->forms->set('edituser', $form);
    }

    public function showcharAction($params = null) {
        $character = Characters::findFirst(["id = ?0", "bind" => [$params]]);
        if (!isset($character->id)) {
            $this->flash->error($this->translate['characters-no_exist']);
            return $this->response->redirect('admin/users');
        }

        // template
        $this->view->pageHeader = $this->translate['characters-label'];
        $this->view->pageDesc = '';
        $this->view->pageUsersId = $character->users->id;

        $form = new EditCharacterForm($character);
        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $character->name = $this->request->getPost('name');
                $character->events = (new ParseString())->htmltobbcode($this->request->getPost('events'));
                $character->equipment = (new ParseString())->htmltobbcode($this->request->getPost('equipment'));
                $character->spells = (new ParseString())->htmltobbcode($this->request->getPost('spells'));
                if ($character->save()) {
                    $this->flash->success($this->translate['characters-save_success']);
                } else $this->flash->error($this->translate['characters-save_error']);

                $this->view->disable();

                return $this->response->redirect('/admin/users/show/'.$character->users_id);
            }
        }
        $this->forms->set('editcharacter', $form);
    }

    public function deleteCharAction($params = null)
    {
        $character = Characters::findFirst(["id = ?0", "bind" => [$params]]);
        if (isset($character->id)) {
            $this->flash->error($this->translate['characters-deleted']);
            $character->delete();
        }

        return $this->response->redirect('admin/characters');
    }
}
