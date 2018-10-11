<?php

namespace Game\Library;

use Phalcon\Dispatcher;
use Main\Models\Session;
use Main\Models\Users;
use Phalcon\Mvc\Model\Resultset;

/**
 * Class everyPage
 * @package Game\Library
 */
class everyPage extends \Phalcon\Mvc\User\Component
{
    public function __construct(Dispatcher $dispatcher)
    {
        $user = $this->auth->getUser();

        // set game date
        if ($this->config->game->params->saveDate != '' && $this->config->game->params->eraDate != '')
        {
            $this -> view -> setVar('eraDate', date('d-m').'-'.(((int)date('Y') - (int)date('Y', $this->config->game->params->saveDate)) + (int)$this->config->game->params->eraDate));
        }
        else $this -> view -> setVar('eraDate', '');

        if (isset($user -> character -> id))
        {
            // send user data to view
            $this->view->setVars(
                [
                    'character' => (object)[
                        'acc' => $user -> id,
                        'id' => $user->character->id,
                        'name' => $user->character->name,
                        'shortname' => explode(" ", trim($user->character->name))[0],
                        'avatar' => $user->character->getAvatar(),
                        'newmsg' => $user->character->newmsg,
                        'newlogs' => $user->character->newlogs,
                        'hp' => $user->character->hp,
                        'max_hp' => $user->character->max_hp,
                        'gold' => $user->character->gold,
                        'location' => $user->character->location_id,
                    ],
                ]
            );
            // Update last activity time
            if ($user -> lpv < (time() - 60)) $this->modelsManager->executeQuery("UPDATE Main\Models\Users SET lpv = ".time()." WHERE id = ?0", array(0 => $user -> id));
        }
        elseif ($dispatcher -> getControllerName() != 'charcreator')  // no active char redirect to charcreator
        {
            $this->view->disable();
            $this->response->redirect('/charcreator/lobby');
        }
    }
}