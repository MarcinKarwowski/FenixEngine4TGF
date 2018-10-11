<?php
/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2015 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

namespace Game\Controller;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Between;
use Main\Models\Characters;
use Main\Models\CharactersNotifications;
use Main\Models\Notifications;
use Main\Models\Chats;
use Main\Models\ChatsCharacters;
use Main\Models\ChatsMessages;
use App\Service\ParseString;
use Main\Models\Users;
use App\Service\Notify;


class ChatController extends ControllerBase
{
    public function indexAction($id = 1, $page = 1)
    {
        $user = $this->auth->getUser();

        $room = Chats::findFirst(["id = ?0", "bind" => [$id]]);
        if (!$room->id)
        {
            $this->view->disable();
            return $this->response->redirect('/game/chat');
        }
        // Add to room if not added
        $presentinroom = ChatsCharacters::findFirst(['character_id= ?0 AND room_id= ?1', 'bind' => [$user->character->id, $id]]);
        if (!isset($presentinroom->id))
        {
            $presentinroom = new ChatsCharacters();
            $presentinroom->character_id = $user->character->id;
            $presentinroom->room_id = $room->id;
            $presentinroom->modified_at = time();
            $presentinroom->save();
        }
        else $presentinroom->save(['modified_at' => time()]);

        // rom data
        $this->view->room_id = $room -> id;
        $this->view->title = html_entity_decode($room->title);
        $this->view->desc = html_entity_decode($room->desc);
        // get chaters
        $this->view->chaters = $room->roomcharacters;
        // get messages
        $messages = $room->getRoommessages(['order' => 'id DESC']);

        // last elem marker
        $this->session->set('chatlast', (!isset($messages->getFirst()->id) ? 0 : (int) $messages->getFirst()->id));

        $paginator = new \Phalcon\Paginator\Adapter\Model(
            array(
                "data"  => $messages,
                "limit" => 25,
                "page"  => (int) $page
            )
        );

        $this->view->messages = $paginator->getPaginate();
    }

    /*
     * chose another room
     */
    public function changeAction($digit = 1)
    {
        $room = Chats::findFirst(['id = ?0', 'bind' => [$digit]]);
        if (!$room->id)
        {
            $this->flash->error($this->translate[ 'chat-session_not_exists ' ]);

            return $this->response->redirect('/game/chat');
        }
        if ($room->hide == 1 && $room->owner_id != $this->auth->getIdentity()[ 'id' ])
        {
            if (count($room->getRoomCharacters('character_id = ' . $this->auth->getIdentity()[ 'activeChar' ])) == 0)
            {
                $this->flash->error($this->translate[ 'chat-session_not_exists ' ]);

                return $this->response->redirect('/game/chat');
            }
        }

        return $this->response->redirect('/game/chat/index/' . $room->id . '/1');
    }

    /*
     * write message on chat
     */
    public function writeAction($digit = 1)
    {
        $user = $this->auth->getUser();

        if ($this->request->isPost() && $digit != 0)
        {
            $room = Chats::findFirst(['id = ?0', 'bind' => [$digit]]);
            if (!$room->id)
            {
                $this->flash->error($this->translate[ 'chat-session_not_exists ' ]);

                return $this->response->redirect('/game/chat');
            }
            if ($room->archived == 1)
            {
                $this->flash->error($this->translate[ 'chat-archived_session_desc ' ]);

                return $this->response->redirect('/game/chat');
            }
            if ($room->hide == 1 && $room->owner_id != $this->auth->getIdentity()[ 'id' ])
            {
                if (count($room->getRoomCharacters('character_id = ' . $this->auth->getIdentity()[ 'activeChar' ])) == 0)
                {
                    $this->flash->error($this->translate[ 'chat-session_not_exists ' ]);

                    return $this->response->redirect('/game/chat');
                }
            }
            // Notify about new posts in session
            if ($room->showinn == 0 && 1 == 2)
            {
                $notifyInfo = $this->db->query('SELECT cn.character_id FROM game_notifications AS gn
                                      LEFT JOIN characters_notifications AS cn ON cn.game_notifications_id=gn.id
                                      WHERE gn.item_id=' . $room->id . ' AND gn.type=\'RPG\' AND cn.readed=0');
                $arrNotifyInfo = [];
                foreach ($notifyInfo as $row)
                {
                    $arrNotifyInfo[ $row[ 'character_id' ] ] = $row[ 'character_id' ];
                }
                foreach (ChatsCharacters::find(['conditions' => 'room_id = ' . $room->id . ' AND character_id != ' . $this->auth->getIdentity()[ 'activeChar' ]]) as $chater)
                {
                    if (!isset($arrNotifyInfo[ $chater->character_id ]))
                    {
                        Notify::send([
                            'title'        => 'Posty w sesji',
                            'text'         => 'W sesji <a href="/game/chat/change/' . $room->id . '">' . $room->title . '</a> ' . $user->character->name . ' dodał/a nowy wpis.',
                            'type'         => 'RPG',
                            'popup'        => 0,
                            'globals'      => 0,
                            'item_id'      => $room->id,
                            'character_id' => $chater->character_id
                        ]);

                        $this->db->query('UPDATE characters SET newlogs=newlogs+1 WHERE id=' . $chater->character_id . ' LIMIT 1');
                    }
                }
            }

            $message = (new ParseString())->bbcodetohtml($this->request->getPost('messageText', 'string'));
            if (strlen($message) < 1) return;

            $newmessage = new ChatsMessages();
            $newmessage->character_id = $user->character->id;
            $newmessage->room_id = $room->id;
            $newmessage->date = time();
            $newmessage->msg = $message;
            $newmessage->save();

            $newmessage->getMessagesRoom()->save(['last_msg_id' => $newmessage->id]);
        }
    }

    /*
     * delete message on chat
     */
    public function deleteAction($digit = null)
    {
        if ($this->acl->checkPermit('chat_delpost') === false)
        {
            $this->view->error = $this->translate[ 'acl-no_permiossion' ];

            return false;
        }

        $message = ChatsMessages::findFirst(['id = ?0', 'bind' => $digit]);
        if ($message->id)
        {
            $message->delete();
        }
    }

    /*
     * Show session list
     */
    public function rpgAction()
    {
        $page = $this->dispatcher->getParam('digit');
        if (empty($page)) $page = 1;

        $builder = $this->modelsManager->createBuilder()
            ->columns(array('Chats.*', 'ChatsMessages.*'))
            ->from(array('ChatsCharacters' => 'Main\Models\ChatsCharacters'))
            ->leftjoin('Main\Models\Chats', 'Chats.id = ChatsCharacters.room_id', 'Chats')
            ->leftjoin('Main\Models\ChatsMessages', 'ChatsMessages.id = Chats.last_msg_id', 'ChatsMessages')
            ->where('ChatsCharacters.character_id = :charID:', array('charID' => $this->auth->getIdentity()[ 'activeChar' ]))
            ->andwhere('Chats.owner_id != 0')
            ->andwhere('Chats.archived = 0')
            ->andwhere('Chats.showinn = 0')
            ->orderBy('Chats.last_msg_id DESC');

        $paginator = new PaginatorQueryBuilder(
            array(
                "builder" => $builder,
                "limit"   => 10,
                "page"    => $page
            )
        );

        // Get the paginated results
        $this->view->page = $paginator->getPaginate();
    }

    /*
     * Get player session list
     */
    public function myrpgAction($digit = 1, $type = 0)
    {
        if ($type != 0 && $type != 1) $type = 0;

        $builder = $this->modelsManager->createBuilder()
            ->columns(array('Chats.*'))
            ->from(array('Chats' => 'Main\Models\Chats'))
            ->where('Chats.owner_id = :accID:', ['accID' => $this->auth->getIdentity()[ 'id' ]])
            ->andwhere('Chats.archived = :type:', ['type' => $type])
            ->orderBy('Chats.id DESC');

        $paginator = new PaginatorQueryBuilder(
            array(
                "builder" => $builder,
                "limit"   => 10,
                "page"    => $digit
            )
        );

        // Get the paginated results
        $this->view->page = $paginator->getPaginate();
        $this->view->rpgtype = $type;
    }

    /*
     * get all public sessions
     */
    public function allrpgAction($digit = 1, $type = 0)
    {
        if ($type != 0 && $type != 1) $type = 0;

        $builder = $this->modelsManager->createBuilder()
            ->columns(array('Chats.*'))
            ->from(array('Chats' => 'Main\Models\Chats'))
            ->where('Chats.owner_id != 0')
            ->andwhere('Chats.archived = :type:', ['type' => $type])
            ->andwhere('Chats.hide = 0')
            ->andwhere('Chats.showinn = 0')
            ->orderBy('Chats.last_msg_id DESC');

        $paginator = new PaginatorQueryBuilder(
            array(
                "builder" => $builder,
                "limit"   => 10,
                "page"    => $digit
            )
        );

        // Get the paginated results
        $this->view->page = $paginator->getPaginate();
        $this->view->type = $type;
    }

    /*
     * Create session
     */
    public function createAction($digit = null)
    {
        if (empty($digit)) $digit = 0;
        $identity = $this->auth->getIdentity();

        $session = Chats::findFirst(['id = ?0', 'bind' => $digit]);
        if (isset($session->id))
        {
            if ($session->owner_id != $identity[ 'id' ] && $identity['group'] != 'Admin')
            {
                $this->flash->error($this->translate[ 'chat-not_ure_session' ]);

                return $this->response->redirect('game/chat/rpg/1');
            }
        }
        else $session = new Chats();

        if ($this->request->isPost())
        {
            $validation = new Validation();
            $validation->add('hide', new Between(array(
                'minimum' => 0,
                'maximum' => 1,
                'message' => $this->translate[ 'chat-validation_between' ]
            )));
            $validation->add('archived', new Between(array(
                'minimum' => 0,
                'maximum' => 1,
                'message' => $this->translate[ 'chat-validation_between' ]
            )));
            $messages = $validation->validate($_POST);
            if (count($messages))
            {
                foreach ($validation->getMessages() as $message)
                {
                    $this->flash->error($message);
                }

                return $this->response->redirect('game/chat/create/' . $session->id);
            }

            $session->title = $this->request->getPost('title', 'striptags');
            $session->showinn = 0;
            $session->owner_id = $this->auth->getIdentity()[ 'id' ];
            $session->desc = (new ParseString())->bbcodetohtml($this->request->getPost('desc', 'string'));
            $session->days = 0;
            $session->hide = $this->request->getPost('hide', 'int');
            $session->archived = $this->request->getPost('archived', 'int');
            if (!$session->save())
            {
                $strMsg = '';
                foreach ($session->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '')
                {
                    $this->flash->error($strMsg);

                    return $this->response->redirect('game/chat/create/' . $session->id);
                }
            }
            // add player to chat
            if (count($session->getRoomCharacters(['conditions' => 'character_id = ' . $this->auth->getIdentity()[ 'activeChar' ]])) == 0)
            {
                $inroom = new ChatsCharacters();
                $inroom->character_id = $this->auth->getIdentity()[ 'activeChar' ];
                $inroom->room_id = $session->id;
                $inroom->modified_at = time();
                $inroom->save();
            }
            // add others to chat if private
            if ($session->hide == 1)
            {
                $arrNewOnes = [];
                if (is_array($this->request->getPost('permissions')))
                {
                    foreach ($this->request->getPost('permissions') as $row)
                    {
                        if ((int) $row == $this->auth->getIdentity()[ 'activeChar' ]) continue;

                        $arrNewOnes[ $row ] = $row;
                    }
                }
                foreach (ChatsCharacters::find(['conditions' => 'room_id = ' . $session->id . ' AND character_id != ' . $this->auth->getIdentity()[ 'activeChar' ]]) as $chater)
                {
                    if (!isset($arrNewOnes[ $chater->character_id ])) $chater->delete();
                    else unset($arrNewOnes[ $chater->character_id ]);
                }
                foreach ($arrNewOnes as $row)
                {
                    $inroom = new ChatsCharacters();
                    $inroom->character_id = (int) $row;
                    $inroom->room_id = $session->id;
                    $inroom->modified_at = time();
                    $inroom->save();

                    // send notify to user
                    $notify = new Notifications();
                    $notify->title = 'Nowa sesja';
                    $notify->text = 'Otrzymałeś dostęp do sesji <a href="/game/chat/change/' . $session->id . '">' . $session->title . '</a>';
                    $notify->type = 'RPG';
                    $notify->popup = 0;
                    $notify->globals = 0;
                    $notify->notifyinfo = (new CharactersNotifications())->setNotificationId($notify->id)->setCharacterId($inroom->character_id)->setReaded(0)->setExpiry(null);
                    $notify->save();

                    $chater = $inroom->getChaters();
                    $chater->newlogs = $chater->newlogs + 1;
                    $chater->save();
                }
            }

            $this->flash->success($this->translate[ 'chat-session_saved' ]);

            return $this->response->redirect('game/chat/create/' . $session->id);
        }
        else
        {
            if (isset($session->desc)) $session->desc = (new ParseString())->htmltobbcode($session->desc);
            $this->view->rpg = $session;
        }
    }
}
