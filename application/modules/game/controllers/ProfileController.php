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

use Game\Models\Players;
use Main\Models\Characters;
use Game\Library\EgoData;
use Main\Models\CharactersHistory;
use App\Service\ParseString;

class ProfileController extends ControllerBase
{

    public function indexAction()
    {
        // wraper dla profilu z gry
    }

    /* next and previous profile */
    public function nextAction($current = 1, $side = 1)
    {
        $char = Characters::findFirst([
            'conditions' => 'id '.($side == 1 ? '< ' : '> ').'?0',
            'order' => 'id ASC',
            "bind"       =>[$current]
        ]);
        if (!isset($char -> id)) return  $this->response->redirect('game/profile/show/'.$current);
        else return  $this->response->redirect('game/profile/show/'.$char -> id);
    }

    public function showAction($charid = null)
    {
        $char = Characters::findFirst([
            "id = ?0",
            "bind" => [$charid]
        ]);
        if (!isset($char -> id))
        {
            $this->flash->error($this->translate[ 'profile-no_char' ]);

            return  $this->response->redirect('game/profile/list/1');
        }

        $this->view->charid = $charid;
        $this->view->avatarPath = $char->getAvatar();
        $this->view->name = $char->name;
        $this->view->users_id = $char->users_id;
        $this->view->user = $char->Users->name;
        $this->view->id = $char->id;
        $this->view->status = $char->status;
        $this->view->level = $char -> level;
        $this->view->pd = $char -> pd;
        $this->view->nextlevel = EgoData::nextLevel($char -> level);
        $this->view->pc = $char -> pc;
        $this->view->equipment = (new ParseString())->htmltobbcode($char -> equipment);
        $this->view->spells = (new ParseString())->htmltobbcode($char -> spells);
        $this->view->events = (new ParseString())->htmltobbcode($char -> events);

        $arrSpecials = [];
        $arrStatsElems = [];
        $specials = Players::find(['conditions' => 'character_id = ?0', 'bind' => [$charid]]);
        foreach ($specials as $row)
        {
            if ($row->page->creator->type != 'stats' ) $arrSpecials[ ] = ['label' => $row->page->creator->name, 'value' => $row->page->name, 'level' => $row->value, 'id' => $row->id];
            else
            {
                if (isset($arrStatsElems[$row->page->creator->name]))
                {
                    $arrStatsElems[$row->page->creator->name][] = ['name' => $row->page->name, 'value' => $row->value, 'desc' => strip_tags($row->page->text), 'id' => $row->id];
                }
                else
                {
                    $arrStatsElems[$row->page->creator->name] = [];
                    $arrStatsElems[$row->page->creator->name][] = ['name' => $row->page->name, 'value' => $row->value, 'desc' => strip_tags($row->page->text), 'id' => $row->id];
                }
            }
        }
        $this->view->specials = $arrSpecials;
        $this->view->stats = $arrStatsElems;
    }

    // update stats
    public function statsupAction($params = null)
    {
        $user = $this->auth->getUser();
        if ($user->character->pc < 1)
        {
            $this -> view -> done = false;
            return true;
        }

        $statement = $this->db->prepare('UPDATE game_cr_players SET `value`=`value`+1 WHERE character_id='.$user->character->id.' AND id=:identification LIMIT 1');
        $statement->bindValue(':identification', $params, \PDO::PARAM_INT);
        $statement->execute();

        // remove pc
        $user->character->pc = $user->character->pc - 1;
        $user->character->save();

        $this -> view -> done = true;
        return true;
    }

    public function listAction($page)
    {

    }

    public function savemoodAction()
    {
        $user = $this->auth->getUser();
        $user->character->save(['status' => $this->request->getPost('value', 'string')]);
    }

    /*
     * History
     */
    public function oneAction($params = null)
    {
        $profile = CharactersHistory::findFirst(['id = ?0', 'bind' => [$params]]);
        if (!isset($profile -> id))
        {
            $this->flash->error($this->translate[ 'profile-no_history' ]);

            return  $this->response->redirect('game/profile/list/1');
        }
        $this -> view -> charprofile = $profile;
        $this->view->charid = $profile -> character_id;
    }

    public function editAction($params = null)
    {
        $profile = CharactersHistory::findFirst(['id = ?0', 'bind' => [$params]]);
        if (isset($profile -> id))
        {
            if ($profile -> character_id != $this->auth->getIdentity()[ 'activeChar' ])
            {
                $this->flash->error($this->translate[ 'profile-not_yours' ]);
                return  $this->response->redirect('game/profile/list/1');
            }
            $this->view->charid = $profile -> character_id;  // show other history links in menu
        }
        else
        {
            $profile = new CharactersHistory();
            $this->view->charid = $this->auth->getIdentity()[ 'activeChar' ]; // show other history links in menu
        }

        if ($this->request->isPost())
        {
            if (strlen($this->request->getPost('title', 'striptags')) == 0)
            {
                $this->flash->error($this->translate[ 'profile-write_title' ]);
                return  $this->response->redirect('game/profile/edit/'.$profile -> id);
            }
            $profile -> title = $this->request->getPost('title', 'striptags');
            $profile -> character_id = $this->auth->getIdentity()[ 'activeChar' ];
            $profile -> place = 'PROFILE';
            $profile -> text = (new ParseString())->bbcodetohtml($this->request->getPost('desc', 'string'));
            $profile -> date = time();
            if (!$profile -> save())
            {
                $strMsg = '';
                foreach ($profile->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }
            else
            {
                $this->flash->success($this->translate[ 'profile-history_success_add' ]);
            }

            return  $this->response->redirect('game/profile/edit/'.$profile -> id);
        }
        else
        {
            if (isset($profile -> text)) $profile -> text = (new ParseString())->htmltobbcode($profile -> text);
            $this -> view -> article = $profile;
        }
    }

    public function delAction($params = null)
    {
        $profile = CharactersHistory::findFirst(['id = ?0', 'bind' => [$params]]);
        if (isset($profile -> id))
        {
            if ($profile -> character_id != $this->auth->getIdentity()[ 'activeChar' ])
            {
                $this->flash->error($this->translate[ 'profile-not_yours' ]);
                return  $this->response->redirect('game/profile/list/1');
            }

            $profile -> delete();
        }
        return  $this->response->redirect('game/profile/show/'.$this->auth->getIdentity()[ 'activeChar' ]);
    }

    public function extendAction($type, $charId)
    {
        if ($this->acl->checkPermit('profile_edit') === false)
        {
            $this->flash->error($this->translate[ 'profile-no_char' ]);

            return  $this->response->redirect('game/profile/list/1');
        }

        $char = Characters::findFirst([
            "id = ?0",
            "bind" => [$charId]
        ]);
        if (!isset($char -> id) || !in_array($type, ['equipment', 'spells', 'events']))
        {
            $this->flash->error($this->translate[ 'profile-no_char' ]);

            return  $this->response->redirect('game/profile/list/1');
        }

        if ($this->request->isPost())
        {
            $char -> {$type} = (new ParseString())->bbcodetohtml($this->request->getPost('desc', 'string'));
            if (!$char -> save())
            {
                $strMsg = '';
                foreach ($char->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }
            else
            {
                $this->flash->success($this->translate[ 'profile-extend_success_add' ]);
            }

            return  $this->response->redirect('game/profile/show/'.$char -> id);
        }
    }
}
