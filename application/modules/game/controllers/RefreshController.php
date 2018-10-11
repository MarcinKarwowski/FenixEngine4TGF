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

use Main\Models\ChatsMessages;
use Main\Models\ChatsCharacters;
use Main\Models\Characters;
use Main\Models\Chats;
use Main\Models\ArticlesComments;
use Game\Library\EgoData;

class RefreshController extends ControllerBase
{
    public function dataAction()
    {
        $this->view->disable();

        //Create a response instance
        $response = new \Phalcon\Http\Response();

        $user = $this->auth->getUser();

        $data = [];
        if (isset($user->character->id))
        {
            $data[ 'newmsg' ] = (int) $user->character->newmsg;
            $data[ 'newlogs' ] = (int) $user->character->newlogs;
        }

        // no need this data when no rpg game
        if ($this->config->game->params->charactersAmount != 0 && isset($user->character->id) && $user->character->max_hp > 0)
        {
            $data[ 'hp' ] = (int) $user->character->hp;
            $data[ 'hpbar' ] = (int) (round($user->character->hp / $user->character->max_hp, 2) * 100);
        }

        // get data controller driven
        $fenixengine = $this->request->getPost('fenixengine'); // return params from uri
        $controller = (string) $fenixengine[ 'controller' ];
        switch ($controller)
        {
            case 'chat':
                if (!isset($fenixengine[ 'param3' ])) $fenixengine[ 'param3' ] = 1;
                $room = Chats::findFirst('id = ' . $fenixengine[ 'param3' ]);
                if (isset($room->id))
                {
                    $data[ 'chat' ] = $room->toArray(['desc']);
                    $data[ 'chat' ][ 'desc' ] = html_entity_decode($data[ 'chat' ][ 'desc' ]); // encode entities
                    foreach ($room->getRoomcharacters(['column' => 'id, name, avatar']) as $chater)
                    {
                        $data[ 'chat' ][ 'users' ][ ] = ['id' => $chater->id, 'name' => $chater->name, 'avatar' => $chater->getAvatar()];
                    }
                    // update activity info
                    $myactivity = ChatsCharacters::findFirst(['conditions' => 'character_id = ' . $user->character->id . ' AND room_id = ' . $room->id . ' AND modified_at < ' . (time() - 480)]);
                    if (isset($myactivity->id))
                    {
                        $myactivity->save(['modified_at' => time()]);
                    }

                    // get chat posts
                    $lastId = $this->session->get('chatlast');
                    if ($lastId !== false)
                    {
                        if ($this->acl->checkPermit('chat_delpost')) $candel = 1;
                        else $candel = 0;
                        foreach ($room->getRoommessages(['conditions' => 'id > ' . (int) $lastId, 'order' => 'id ASC']) as $post)
                        {
                            $data[ 'chat' ][ 'posts' ][ ] = ['id' => $post->id, 'msg' => $post->msg, 'date' => date('d-m-Y H:i:s', $post->date), 'writer' => $post->writer->id, 'name' => $post->writer->name, 'avatar' => $post->writer->getAvatar(), 'candel' => $candel];
                            $this->session->set('chatlast', (int) $post->id);
                        }
                    }
                }
                break;
            case 'index':

                break;
        }

        // delete inactive chat users
        $chaters = ChatsCharacters::find(['conditions' => 'room_id=1 AND modified_at < ' . (time() - 600)]);
        $chaters->delete();

        /*
        * Get game EGO data
        */
        $ego = new EgoData();
        $data[ 'ego' ] = $ego->getRefresh(['controller' => $controller], $user);

        //Set the content of the response
        $response->setContent(json_encode($data));

        //Return the response
        return $response;
    }

    public function getusersAction()
    {
        $this->view->disable();

        //Create a response instance
        $response = new \Phalcon\Http\Response();

        $seek = $this->request->getQuery('term');
        if (is_numeric($seek)) $arrSeek = ['conditions' => 'users_id = ?1', 'bind' => [1 => (int) $seek]];
        else $arrSeek = ['conditions' => 'users_id != 0 AND name LIKE ?1', 'bind' => [1 => "%" . $this->request->getQuery('term', 'string') . "%"]];
        //Set the content of the response
        $response->setContent(json_encode(Characters::find(array(
            'columns'    => array('name AS value', 'id'),
            "conditions" => $arrSeek[ 'conditions' ],
            'order'      => 'name',
            "bind"       => $arrSeek[ 'bind' ]
        ))->toArray()));

        //Return the response
        return $response;
    }
}
