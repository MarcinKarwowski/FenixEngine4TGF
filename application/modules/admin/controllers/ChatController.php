<?php

namespace Admin\Controller;

use Main\Models\Chats;

class ChatController extends ControllerBase
{
    /**
     * Dashboard
     */
    public function indexAction()
    {
        // template
        $this -> view -> pageHeader = $this -> translate['chat-list'];
        $this -> view -> pageDesc = '';

    }

    public function deleteAction($params = null)
    {
        $chat = Chats::findFirst(["id = ?0", "bind" => [$params]]);
        if (isset($chat -> id))
        {
            if ($chat -> id == 1)
            {
                $this->flash->error($this->translate[ 'chat-cantdel' ]);
                return $this->response->redirect('admin/chat');
            }
            $this->flash->error($this->translate[ 'chat-deleted' ]);
            $chat -> delete();
        }

        return $this->response->redirect('admin/chat');
    }
}
