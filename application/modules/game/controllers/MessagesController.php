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

use Main\Models\Characters;
use Main\Models\Messages;
use Main\Models\MessagesText;
use Phalcon\Db\RawValue;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use App\Service\ParseString;

class MessagesController extends ControllerBase
{
    public function indexAction()
    {
        $user = $this->auth->getUser();

        if ($user->character->newmsg > 0) {
            $user->character->save(['newmsg' => 0]);
        }

        $page = (int)$this->dispatcher->getParam('digit');
        if (empty($page)) $page = 1;

        $builder = $this->modelsManager->createBuilder()
            ->columns(array('MAX(Messages.id) AS mid', 'MAX(Messages.date) AS date', 'Messages.topic', 'ANY_VALUE(Messages.readed)', 'Sender.*'))
            ->from(array('Messages' => 'Main\Models\Messages'))
            ->leftjoin('Main\Models\Characters', 'Messages.sender_id = Sender.id', 'Sender')
            ->where('Messages.character_id = :charID:', array('charID' => $this->auth->getIdentity()['activeChar']))
            ->andwhere('Messages.sended = 0')
            ->orderBy('MAX(Messages.id) DESC')
            ->groupBy(array('Messages.topic', 'Messages.sender_id'));

        // Create a Model paginator
        $paginator = new PaginatorQueryBuilder(
            array(
                "builder" => $builder,
                "limit" => 10,
                "page" => $page
            )
        );

        // Get the paginated results
        $paginate = $paginator->getPaginate();
        $paginate->next = $paginate->current + 1;
        $paginate->before = $paginate->current - 1 > 0 ? $paginate->current - 1 : 1;
        $paginate->total_items = Messages::count(array('character_id = ' . $this->auth->getIdentity()['activeChar'].' AND sended=0'));
        $paginate->total_pages = ceil($builder->getQuery()->execute()->count() / 10);
        $paginate->last = $paginate->total_pages;
        $this->view->page = $paginate;
    }

    public function writeAction()
    {
        if ($this->request->isPost()) {
            // check receiver
            $receiver = Characters::findFirst(['id = ?0', 'bind' => $this->request->getPost('ReceiverID', 'int')]);
            if (!isset($receiver->id)) {
                $this->flash->error($this->translate['no-character']);

                return $this->response->redirect('game/messages/write');
            }
            // inbox overflow
            if ($receiver -> getMyMessages('sended=0') -> count()+1 > $this -> config -> modules -> messages -> inboxlimit)
            {
                $this->flash->error($this->translate['messages-inbox_overflow']);

                return $this->response->redirect('game/messages/write');
            }

            // save msg
            if ($this -> saveMsg(
                [
                    'character_id' => $receiver->id,
                    'sender_id' => $this->auth->getIdentity()['activeChar'],
                    'date' => new RawValue('UNIX_TIMESTAMP()'),
                    'topic' => $this->request->getPost('messageTopic', 'striptags'),
                    'readed' => 0,
                    'sended' => 0
                ],
                $this->request->getPost('messageText', 'string')
            ) === false)
            {
                return $this->response->redirect('game/messages/write');
            }

            // receiver send info
            $receiver->save(['newmsg' => ($receiver->newmsg + 1)]);

            $this->flash->success($this->translate['messages-msg_sended']);
            return $this->response->redirect('game/messages/write');
        }
    }

    public function readAction()
    {
        $msgID = (int)$this->dispatcher->getParam('digit');
        $user = $this->auth->getUser();
        $msg = $user -> character -> getMyMessages('id='.$msgID)-> getFirst();
        if (!isset($msg -> id))
        {
            $this->flash->error($this -> translate['messages-no_msg']);

            return $this->response->redirect('game/messages/index');
        }
        // mark as readed
        if ($msg -> readed == 0) $msg -> save(['readed' => 1]);

        if ($this->request->isPost()) {

            // check receiver
            $receiver = $msg -> Sender;
            if (!isset($receiver->id)) {
                $this->flash->error($this->translate['no-character']);

                return $this->response->redirect('game/messages/write');
            }

            // save msg
            if ($this -> saveMsg(
                [
                    'character_id' => $receiver->id,
                    'sender_id' => $this->auth->getIdentity()['activeChar'],
                    'date' => new RawValue('UNIX_TIMESTAMP()'),
                    'topic' => $msg -> topic,
                    'readed' => 0,
                    'sended' => 0
                ],
                $this->request->getPost('respond', 'string')
            ) === false)
            {
                return $this->response->redirect('game/messages/index');
            }

            // receiver send info
            $receiver->save(['newmsg' => ($receiver->newmsg + 1)]);

            $this->flash->success($this->translate['messages-msg_sended']);
            return $this->response->redirect('game/messages/index');
        }
        else
        {
            $this -> view -> msg = $msg;
            $this -> view -> msgtext = $msg -> getMyMessageText() -> text;
            if ($msg -> sended == 1) $this -> view -> author = (object)['name' => $msg -> Owner -> name, 'avatar' => $msg -> Owner -> getAvatar()];
            else
            {
                if ($msg -> Sender -> name) $this -> view -> author = (object)['name' => $msg -> Sender -> name, 'avatar' => $msg -> Sender -> getAvatar()];
                else $this -> view -> author = (object)['name' => $this -> translate['someone'], 'avatar' => '/assets/images/defaultav.jpg', 'npc' => true];
            }

            // get whole topic with pagination
            $page = (int)$this->dispatcher->getParam('page');
            if (empty($page)) $page = 1;

            // Create a Model paginator, show 10 rows by page starting from $currentPage
            $paginator = new PaginatorQueryBuilder(
                array(
                    "builder" => $this->modelsManager->createBuilder()
                        ->columns(array('Messages.*', 'MyMessageText.*', 'Sender.*'))
                        ->from(array('Messages' => 'Main\Models\Messages'))
                        ->leftjoin('Main\Models\Characters', 'Messages.sender_id = Sender.id', 'Sender')
                        ->leftjoin('Main\Models\MessagesText', 'Messages.id = MyMessageText.message_id', 'MyMessageText')
                        ->where('Messages.character_id = :charID:', array('charID' => $this->auth->getIdentity()['activeChar']))
                        ->andwhere('Messages.topic = :topic:', array('topic' => $msg -> topic))
                        ->andwhere('Messages.sender_id = :senderid:', array('senderid' => $msg -> sender_id))
                        ->orderBy('Messages.id DESC'),
                    "limit" => 8,
                    "page" => $page
                )
            );

            // Get the paginated results
            $this->view->page = $paginator->getPaginate();
        }
    }

    private function saveMsg($arrData=null, $text)
    {
        if (!$arrData || strlen($text) < 1 || strlen($arrData['topic']) < 1)
        {
            $this->flash->error($this -> translate['messages-write_something']);

            return false;
        }

        // send msg to a receiver
        $msg = (new Messages())->assign($arrData);

        if (!$msg->save()) {
            $strMsg = '';
            foreach ($msg->getMessages() as $message) {
                $strMsg = $strMsg . $message . '<br />';
            }
            if ($strMsg != '') {
                $this->flash->error($strMsg);

                return false;
            }
        }
        (new MessagesText())->save(['message_id' => $msg -> id, 'text' => (new ParseString())->bbcodetohtml($text)]);

        // save in sended
        $arrData['sender_id'] = $arrData['character_id'];
        $arrData['character_id'] = $this->auth->getIdentity()['activeChar'];
        $arrData['sended'] = 1;

        $sended = (new Messages())->assign($arrData);
        $sended -> save();
        (new MessagesText())->save(['message_id' => $sended -> id, 'text' => (new ParseString())->bbcodetohtml($text)]);

        return true;
    }

    /*
     * list of sended msg
     */
    public function sendedAction()
    {
        // get whole topic with pagination
        $page = $this->dispatcher->getParam('digit');
        if (empty($page)) $page = 1;

        // Create a Model paginator, show 10 rows by page starting from $currentPage
        $paginator = new PaginatorQueryBuilder(
            array(
                "builder" => $this->modelsManager->createBuilder()
                    ->columns(array('Messages.*', 'Sender.*'))
                    ->from(array('Messages' => 'Main\Models\Messages'))
                    ->leftjoin('Main\Models\Characters', 'Messages.sender_id = Sender.id', 'Sender')
                    ->where('Messages.character_id = :charID:', array('charID' => $this->auth->getIdentity()['activeChar']))
                    ->andwhere('Messages.sended = 1')
                    ->orderBy('Messages.id DESC'),
                "limit" => 10,
                "page" => $page
            )
        );

        // Get the paginated results
        $this->view->page = $paginator->getPaginate();
    }

    public function deleteAction()
    {
        $msgid = $this->dispatcher->getParam('digit', 'int');
        $deltype = $this->dispatcher->getParam('type', 'int');

        $user = $this->auth->getUser();
        $msg = $user -> character -> getMyMessages('id='.$msgid)-> getFirst();
        if (!isset($msg -> id))
        {
            $this->flash->error($this -> translate['messages-no_msg']);

            return $this->response->redirect('game/messages/index');
        }

        // whole topic
        if ($deltype == 1)
        {
            $i = 0;
            $allmsg = $user -> character -> getMyMessages(["topic='".$msg -> topic."' AND sender_id=".$msg -> sender_id." AND saved IS NULL"]);
            foreach($allmsg as $onemsg)
            {
                $onemsg -> delete();
                $i++;
            }

            $this->flash->error(sprintf($this -> translate['messages-topic_deleted'], $i));

            return $this->response->redirect('game/messages/index');
        }
        // del one msg
        elseif ($deltype == 2)
        {
            $msg -> delete();

            $this->view->disable();

            //Create a response instance
            $response = new \Phalcon\Http\Response();

            $msgcount = $user -> character -> getMyMessages(["topic='".$msg -> topic."' AND sender_id=".$msg -> sender_id.""]) -> count();
            if ($msgcount == 0) $this->flash->error($this -> translate['messages-msg_deleted']);

            $data = [];
            $data['counted'] = $msgcount;

            //Set the content of the response
            $response->setContent(json_encode($data));

            //Return the response
            return $response;
        }
    }

    public function savedAction()
    {
        // get whole topic with pagination
        $page = $this->dispatcher->getParam('digit');
        if (empty($page)) $page = 1;

        $builder = $this->modelsManager->createBuilder()
            ->columns(array('MAX(Messages.id) AS mid', 'MAX(Messages.date) AS date', 'Messages.topic', 'Sender.*'))
            ->from(array('Messages' => 'Main\Models\Messages'))
            ->leftjoin('Main\Models\Characters', 'Messages.sender_id = Sender.id', 'Sender')
            ->where('Messages.character_id = :charID:', array('charID' => $this->auth->getIdentity()['activeChar']))
            ->andwhere('Messages.saved = 1')
            ->orderBy('MAX(Messages.id) DESC')
            ->groupBy(array('Messages.topic', 'Messages.sender_id'));

        // Create a Model paginator, show 15 rows by page starting from $currentPage
        $paginator = new PaginatorQueryBuilder(
            array(
                "builder" => $builder,
                "limit" => 10,
                "page" => $page
            )
        );

        // Get the paginated results
        $paginate = $paginator->getPaginate();
        $paginate->next = $paginate->current + 1;
        $paginate->before = $paginate->current - 1 > 0 ? $paginate->current - 1 : 1;
        $paginate->total_items = Messages::count(array('character_id = ' . $this->auth->getIdentity()['activeChar'].' AND sended=0'));
        $paginate->total_pages = ceil($builder->getQuery()->execute()->count() / 10);
        $paginate->last = $paginate->total_pages;
        $this->view->page = $paginate;
    }

    public function saveAction()
    {
        $msgid = $this->dispatcher->getParam('digit', 'int');
        $savetype = $this->dispatcher->getParam('type', 'int');

        $user = $this->auth->getUser();
        $msg = $user -> character -> getMyMessages('id='.$msgid)-> getFirst();
        if (!isset($msg -> id))
        {
            $this->flash->error($this -> translate['messages-no_msg']);

            return $this->response->redirect('game/messages/index');
        }
        if ($msg -> saved == 1)
        {
            $this->flash->error($this -> translate['messages-topic_already_saved']);

            return  $this->response->redirect('game/messages/read/'.$msg -> id);
        }

        // whole topic
        if ($savetype == 1)
        {
            $i = 0;
            $allmsg = $user -> character -> getMyMessages(["topic='".$msg -> topic."' AND sender_id=".$msg -> sender_id." AND saved IS NULL"]);
            foreach($allmsg as $onemsg)
            {
                $onemsg -> save(['saved' => 1]);
                $i++;
            }

            $this->flash->error(sprintf($this -> translate['messages-topic_saved'], $i));

            return $this->response->redirect('game/messages/read/'.$msg -> id);
        }
        // del one msg
        elseif ($savetype == 2)
        {
            $msg -> save(['saved' => 1]);

            $this->view->disable();

            //Create a response instance
            $response = new \Phalcon\Http\Response();

            $data = [];
            $data['saved'] = 1;

            //Set the content of the response
            $response->setContent(json_encode($data));

            //Return the response
            return $response;
        }
    }
}
