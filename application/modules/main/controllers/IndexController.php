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

namespace Main\Controller;

use Main\Models\Articles;

class IndexController extends ControllerBase
{
    public function indexAction()
    {

    }

    public function errorAction()
    {
        $exception = $this -> dispatcher ->  getParam('error');

        //Handle 404 exceptions
        if (is_object($exception) && get_class($exception) == 'Phalcon\Mvc\Dispatcher\Exception') {
            $exception = $this -> translate['404-error'];
        }
        $this -> view -> error = $exception;
    }

    /*
     * Show one article
     */
    public function showAction($id = null)
    {
        $article = Articles::findFirst(["id = ?0", "bind" => [$id]]);
        if (!isset($article -> id))
        {
            $this->flash->error($this -> translate['news-no_art']);
            return $this->response->redirect('');
        }
        $this -> view -> article = $article;
    }

    /*
     * redirect auth user to game
     */
    public function playAction()
    {
        $identity = $this->auth->getIdentity();
        if ($identity)
        {
            if (is_file(APPLICATION_PATH.'/modules/game/Module.php'))
            {
                return $this->response->redirect((isset($this -> config -> game -> params -> defaultPage) ? $this -> config -> game -> params -> defaultPage : '/game'));
            }
            else
            {
                if ($identity['group'] == 'Admin')
                {
                    $this->flash->error($this -> translate['no-game-admin']);
                    return $this->response->redirect('/admin');
                }
                else
                {
                    $this->flash->error($this -> translate['no-game-user']);
                    return $this->response->redirect('');
                }
            }

        }
        else
        {
            $this->flash->error($this -> translate['acl-not-logged']);
            return $this->response->redirect('');
        }
    }
}
