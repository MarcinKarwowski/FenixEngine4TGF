<?php

namespace Admin\Controller;

use Main\Models\Notifications;
use Main\Models\CharactersNotifications;
use App\Service\ParseString;

class NotifyController extends ControllerBase
{
    /**
     * Dashboard
     */
    public function indexAction()
    {
        // template
        $this -> view -> pageHeader = $this -> translate['notify-text'];
        $this -> view -> pageDesc = $this -> translate['notify-text_desc'];
    }

    public function editAction($params = null)
    {
        $this->view->modalTitle = $this->translate[ 'notify-edit' ];

        $this->view->obj = Notifications::findFirst(["id = ?0", "bind" => [$params]]);
    }

    public function saveAction($params = null)
    {
        if ($this->request->isPost())
        {
            if ($params) $notify = Notifications::findFirst(["id = ?0", "bind" => [$params]]);
            else $notify = new Notifications();

            $notify->title = $this->request->getPost('title', 'striptags');
            $notify->text = (new ParseString())->bbcodetohtml($this->request->getPost('text'));
            $notify->type = 'MASS';
            $notify->popup = 0;
            $notify->globals = 1;
            $notify->date = time();

            if (!$notify->save())
            {
                $strMsg = '';
                foreach ($notify->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }

            return $this->response->redirect('admin/notify');
        }

        $this -> view -> pageHeader = $this -> translate['notify-text'];
        $this -> view -> pageDesc = $this -> translate['notify-text_desc'];
    }

    public function deleteAction($params = null)
    {
        $notify = Notifications::findFirst(["id = ?0", "bind" => [$params]]);
        if (isset($notify -> id))
        {
            $this->flash->error($this->translate[ 'notify-deleted' ]);
            $notify -> delete();
        }

        return $this->response->redirect('admin/notify');
    }
}
