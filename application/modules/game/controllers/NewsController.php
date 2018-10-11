<?php

/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2016 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

namespace Game\Controller;

use Main\Models\Articles;
use Main\Models\ArticlesComments;
use App\Service\ParseString;

class NewsController extends ControllerBase
{
    /**
     * Dashboard
     */
    public function indexAction($digit = 1)
    {
        $paginator = new \Phalcon\Paginator\Adapter\Model(
            array(
                "data"  => Articles::find(['conditions' => 'published = 1', 'order' => 'id DESC']),
                "limit" => 25,
                "page"  => (int) $digit
            )
        );

        $this->view->page = $paginator->getPaginate();
    }

    /*
     * One article view
     */
    public function showAction($digit = null, $page = 1)
    {
        $article = Articles::findFirstById($digit);
        if (!isset($article -> id) || $article -> published != 1)
        {
            $this->flash->error($this->translate[ 'news-no_art' ]);

            return $this->response->redirect('game/news/index');
        }

        $this -> view -> article = $article;
        $this -> view -> text = $article -> Textdata -> text;

        // get comments
        $paginator = new \Phalcon\Paginator\Adapter\Model(
            array(
                "data"  => ArticlesComments::find(['conditions' => 'articles_id = ?1', "bind" => array(1 => $digit), 'order' => 'id DESC']),
                "limit" => 25,
                "page"  => $page
            )
        );

        $this -> view -> comments  = $paginator->getPaginate();
    }

    /*
     * save comment
     */
    public function commentAction($digit = null)
    {
        $article = Articles::findFirstById($digit);
        if (!isset($article -> id) || $article -> published != 1)
        {
            $this->flash->error($this->translate[ 'news-no_art' ]);

            return $this->response->redirect('game/news/index');
        }

        $comment = new ArticlesComments();
        $comment -> articles_id = $article -> id;
        $comment -> character_id = $this->auth->getIdentity()[ 'activeChar' ];
        $comment -> published = 1;
        $comment -> publishdate = time();
        $comment -> text = (new ParseString())->bbcodetohtml($this->request->getPost('messageText', 'string'));

        $comment -> save();

        return $this->response->redirect('game/news/show/' . $article->id);
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

        $message = ArticlesComments::findFirst(['id = ?0', 'bind' => [$digit]]);
        if ($message->id)
        {
            $message->delete();
        }

        return $this->response->redirect('game/news/show/' . $message->articles_id);
    }
}
