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

namespace Main\Controller\Ingame;

class WikipediaController extends \Game\Controller\ControllerBase
{
    public function indexAction()
    {
        $this->view->pageHeader = $this->translate[ 'wikipedia-text' ];
    }



    /*
     * redirect auth user to game
     */
    public function articleAction()
    {
        $identity = $this->auth->getIdentity();
        if ($identity)
        {

        }
        else
        {
            $this->flash->error($this -> translate['acl-not-logged']);
            return $this->response->redirect('');
        }
    }
}
