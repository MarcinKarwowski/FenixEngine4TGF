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

use Game\Models\Creations;
use Game\Models\Creator;
use Game\Models\Relations;
use Game\Models\Players;
use Phalcon\Mvc\Dispatcher;
use Game\Library\EgoData;
use Game\Models\Skils;
use Main\Models\Characters;
use Game\Models\CharactersSkils;
use Game\Models\CharactersAbilities;
use App\Service\Auth;

class CharcreatorController extends ControllerBase
{
    private $user;
    public $ego;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        parent::beforeExecuteRoute($dispatcher);

        // get user obj
        $this->user = $this->auth->getUser();

        // charcreator is offline
        if ($this->config->game->params->characterNeed === false)
        {
            $this->flash->error($this->translate[ 'charcreator-offline' ]);

            return $this->response->redirect('/play');
        }
        else if ($this->config->game->params->characterNeed === true && $this->config->game->params->charactersAmount == 0 && !is_int($this->auth->getIdentity()[ 'activeChar' ]))
        {
            // create character
            $char = new Characters();
            $char->users_id = $this->user->id;
            $char->active = 1;
            $char->name = $this->user->name;
            $char->gender = 'M';
            $char->newlogs = 0;
            $char->newmsg = 0;
            $char->hp = $this->config->game->params->beginhp;
            $char->max_hp = $this->config->game->params->beginhp;
            $char->gold = $this->config->game->params->begingold;
            $char->chatroom = 0;
            $char->location_id = 1;

            if (!$char->save())
            {
                $strMsg = '';
                foreach ($char->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '')
                {
                    $this->view->error = $strMsg;
                }

                return $this->response->redirect('/');
            }

            $this->user->active_characters_id = $char->id;
            $this->user->save();

            $this->auth->authUserById($this->user->id);

            $this->flash->success($this->translate[ 'hello' ] . ' ' . $char->name . '.');

            return $this->response->redirect('/play');
        }
        // EGO obj
        $this->ego = new EgoData();
    }

    // Show charcreator lobby
    public function lobbyAction()
    {
        $this->view->pageHeader = $this->translate[ 'charcreator-lobby' ];
        // user characters
        $this->view->userChars = $this->user->characters;
    }

    // creator menu
    public function beginAction($step = 1)
    {
        // send error
        if ($step != 1 && !isset($this->persistent->step) || $step != 1 && isset($this->persistent->step) && $this->persistent->step < ($step - 1))
        {
            $this->view->error = $this->translate[ 'charcreator-wrong_step' ];

            return true;
        }
        // reset step if back in menu
        if ($this->persistent->step >= $step) $this->persistent->step = ($step-1);

        // get ego data
        $charCreator = Creator::find(['conditions' => 'showincreator = 1', 'order' => 'orderid ASC']);

        // get labels for creator menu
        $arrlabels = [];
        $stepvalid = false;
        $defaultlevel = 1;
        $i = 1;
        foreach ($charCreator as $row)
        {
            if ($i == $step)
            {
                $stepvalid = $row->id;
                $type = $row->type;
                $this->view->$type = 1;
                $this->view->text = $row->text;
                if ($type == 'stats')
                {
                    $params = json_decode($row->params, true);
                    $defaultlevel = $params[ 'basepoints' ];
                    $this->view->freepoints = $params[ 'freepoints' ];
                }
            }
            $arrlabels[ ] = ['name' => $row->name, 'active' => ($i <= $step ? 'active' : 'inactive'), 'order' => $i, 'type' => $row->type];
            $i++;
        }
        // last step
        $arrlabels[ ] = ['name' => $this->translate[ 'charcreator-summary' ], 'active' => ($i <= $step ? 'active' : 'inactive'), 'order' => $i, 'type' => 'chardata'];
        if ($i == $step)
        {
            $this->view->chardata = 1;
            $stepvalid = true;
        }
        if ($stepvalid === false)
        {
            $this->view->error = $this->translate[ 'charcreator-no_step' ];

            return true;
        }

        // get cr choices
        $arrChoices = $this->session->get("cr-choices");

        $subpages = [];
        if ($i == $step)
        {
            $label = null;
            foreach ($arrChoices as $key => $choice)
            {
                $item = Creations::findFirst($key);
                if (isset($item->id))
                {
                    $subpages[ ] = ['name' => $item->name, 'label' => ($item->creator->name == $label ? null : $item->creator->name), 'level' => (isset($choice[ 'level' ]) ? $choice[ 'level' ] : null)];
                    $label = $item->creator->name;
                }
            }
        }
        else
        {
            foreach (Creations::find(['conditions' => 'category_id = ' . $stepvalid]) as $key => $page)
            {
                $allowcreation = true;
                foreach ($page->links as $link)
                {
                    if (!isset($arrChoices[ $link->link_page_id ])) $allowcreation = false;
                    else
                    {
                        if ($arrChoices[ $link->link_page_id ] < $link->value) $allowcreation = false;
                    }
                }
                if ($allowcreation === false) continue;
                if ($page->wiki_id != 0)
                {
                    $page->text = $page->wiki->wikitext->text;
                }
                if ($type == 'stats')
                {
                    $page->text = strip_tags($page->text);
                    if (!is_array($page->params)) $page->params = [];
                    $page->params[ 'basepoints' ] = $defaultlevel;
                }
                $subpages[ ] = $page->toArray();
            }
        }

        $this->view->creations = $subpages;
        $this->view->labels = $arrlabels;

        $this->view->step = (int) $step;
    }

    public function nextAction($step)
    {
        // send error
        if ($step != 1 && !isset($this->persistent->step) || $step != 1 && isset($this->persistent->step) && $this->persistent->step < ($step - 1))
        {
            $this->view->error = $this->translate[ 'charcreator-wrong_step' ];

            return true;
        }
        if ($step == 1) $this->session->remove("cr-choices");
        // reset step if back in menu
        if ($this->persistent->step >= $step) $this->persistent->step = ($step-1);

        // get ego data
        $charCreator = Creator::find(['conditions' => 'showincreator = 1', 'order' => 'orderid ASC']);

        // get labels for creator menu
        $stepvalid = false;
        $i = 1;
        foreach ($charCreator as $row)
        {
            if ($i == $step)
            {
                $stepvalid = $row;
            }
            $i++;
        }
        if ($step >= $i) $stepvalid = (object) ['type' => 'chardata'];
        if ($stepvalid === false)
        {
            $this->view->error = $this->translate[ 'charcreator-no_step' ];

            return true;
        }

        $arrChoices = (!is_array($this->session->get("cr-choices")) ? [] : $this->session->get("cr-choices"));
        foreach ($arrChoices as $key => $choice)
        {
            if ($choice[ 'step' ] >= $step) unset($arrChoices[ $key ]);
        }
        // save stats to session
        if ($stepvalid->type == 'stats')
        {
            $stepvalid->params = json_decode($stepvalid->params, true);

            $sumStats = 0;

            foreach ($stepvalid->options as $row)
            {
                if ($stepvalid->params[ 'basepoints' ] > $this->request->getPost('stat_' . $row->id, 'int'))
                {
                    $this->view->error = $this->translate[ 'charcreator-wrong_stat' ] . ': ' . $row->name;

                    return true;
                }
                $sumStats = $sumStats + $this->request->getPost('stat_' . $row->id, 'int');
                $arrChoices[ $row->id ] = ['level' => $this->request->getPost('stat_' . $row->id, 'int'), 'step' => $step];
            }
            if ($sumStats > (count($arrChoices) * $stepvalid->params[ 'basepoints' ]) + $stepvalid->params[ 'freepoints' ])
            {
                $this->view->error = $this->translate[ 'charcreator-stat_to_high' ];

                return true;
            }
        }
        // save elems
        elseif ($stepvalid->type == 'list')
        {
            $elem = (int) $this->request->getPost('elem', 'int');
            if ($elem == 0)
            {
                $this->view->error = $this->translate[ 'charcreator-chose_something' ];

                return true;
            }
            $arrChoices[ $elem ] = ['id' => $elem, 'step' => $step];
        }
        elseif ($stepvalid->type == 'chardata')
        {
            // check if create can happen
            if (count($this->user->characters) + 1 > $this->config->game->params->charactersAmount)
            {
                $this->view->error = $this->translate[ 'charcreator-too_many_chars' ];

                return true;
            }
            if (!$this->session->has("cr-choices"))
            {
                $this->view->error = $this->translate[ 'charcreator-somethings_is_wrong' ];

                return true;
            }
            $charName = preg_replace('/[^A-Za-zĘÓĄŚŁŻŹĆŃęóąśłżźćń \s]/i', '', $this->request->getPost('charName'));
            if (strlen($charName) < 2)
            {
                $this->view->error = $this->translate[ 'charcreator-bad_name' ];

                return true;
            }

            // create character
            $char = new Characters();
            $char->users_id = $this->user->id;
            $char->active = 1;
            $char->name = trim($charName);
            $char->gender = 'M';
            $char->newlogs = 0;
            $char->newmsg = 0;
            $char->hp = 0;
            $char->max_hp = 0;
            $char->gold = 0;
            $char->chatroom = 0;
            $char->location_id = 1;

            if (!$char->save())
            {
                $strMsg = '';
                foreach ($char->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '')
                {
                    $this->view->error = $strMsg;
                }

                return true;
            }
            // add skils
            if (count($this->session->get("cr-choices")) > 0)
            {
                foreach ($this->session->get("cr-choices") as $key => $choice)
                {
                    $player = new Players();
                    $player->page_id = $key;
                    $player->character_id = $char->id;
                    $player->value = (isset($choice[ 'level' ]) ? $choice[ 'level' ] : 0);
                    $player->save();
                }
            }

            // remove session data
            $this->session->remove("cr-choices");

            $this->view->endCr = 1;

            return true;

        }

        $this->session->set("cr-choices", $arrChoices);
        // next step
        $this->persistent->step = $step + 1;

        $this->dispatcher->forward(array(
            "action" => "begin",
            "params" => array("step" => $this->persistent->step)
        ));

        return true;
    }

    public function choseAction($charID = null)
    {
        if (empty($charID))
        {
            $this->flash->error($this->translate[ 'no-character' ]);

            return $this->response->redirect('/charcreator/lobby');
        }
        $characters = $this->user->getCharacters(array('id=' . $charID, 'limit' => 1));
        if (count($characters) == 0)
        {
            $this->flash->error($this->translate[ 'no-character' ]);

            return $this->response->redirect('/charcreator/lobby');
        }
        $character = $characters[ 0 ];

        $this->user->active_characters_id = $character->id;
        $this->user->save();

        $this->auth->authUserById($this->user->id);

        $this->flash->success($this->translate[ 'hello' ] . ' ' . $character->name . '.');

        return $this->response->redirect('/play');
    }

    public function editAction($charID = null)
    {
        if (empty($charID))
        {
            $this->flash->error($this->translate[ 'no-character' ]);

            return $this->response->redirect('/charcreator/lobby');
        }
        $characters = $this->user->getCharacters(array('id=' . $charID, 'limit' => 1));
        if (count($characters) == 0)
        {
            $this->flash->error($this->translate[ 'no-character' ]);

            return $this->response->redirect('/charcreator/lobby');
        }
        $character = $characters[ 0 ];

        if ($this->request->isPost())
        {
            $charName = preg_replace('/[^A-Za-zĘÓĄŚŁŻŹĆŃęóąśłżźćń \s]/i', '', $this->request->getPost('charName'));
            $character->update(['name' => $charName]);
            $this->view->endCr = 1;

            return true;
        }

        $this->view->charname = $character->name;
        $this->view->charid = $character->id;

        return true;
    }

    public function deleteAction($charID = null)
    {
        if (empty($charID))
        {
            $this->flash->error($this->translate[ 'no-character' ]);

            return $this->response->redirect('/charcreator/lobby');
        }
        $characters = $this->user->getCharacters(array('id=' . $charID, 'limit' => 1));
        if (count($characters) == 0)
        {
            $this->flash->error($this->translate[ 'no-character' ]);

            return $this->response->redirect('/charcreator/lobby');
        }
        $character = $characters[ 0 ];

        $character -> update(['users_id' => 0]);
        // remove avatar
        //if (is_file(PUBLIC_PATH.$this -> config -> url -> staticBaseUri.'static/avatars/'.$this -> avatar))
        //{
        //    unlink(PUBLIC_PATH.$this -> config -> url -> staticBaseUri.'static/avatars/'.$this -> avatar);
        //}
        // remove active character
        $this->user->update(['active_characters_id' => 0]);

        $this->auth->authUserById($this->user->id);

        return $this->response->redirect('/charcreator/lobby');
    }

}
