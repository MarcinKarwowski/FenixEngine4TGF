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

use Main\Models\Wikipedia as Wiki;

class WikipediaController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->pageHeader = $this->translate[ 'wiki-page_title' ];
    }


    /*
     * redirect auth user to game
     */
    public function articleAction($params = null)
    {
        $article = Wiki::findFirst($params);

        if (isset($article->id))
        {
            $this->view->pageHeader = $article->title;
            $this->view->artid = $article -> id;
            $this->view->parent_id = $article -> parent_id;
            $this->view->title = $article->title;
            $this->view->text = $article->wikitext->text;
        }
    }
}
