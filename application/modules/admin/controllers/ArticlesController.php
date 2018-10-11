<?php

namespace Admin\Controller;

use Main\Models\Articles;
use Main\Models\ArticlesText;

class ArticlesController extends ControllerBase
{
    /**
     * Dashboard
     */
    public function indexAction()
    {
        // template
        $this -> view -> pageHeader = $this -> translate['articles-text'];
        $this -> view -> pageDesc = $this -> translate['articles-text_desc'];
    }

    public function editAction($params = null)
    {
        $this->view->modalTitle = $this->translate[ 'articles-edit' ];

        $article = Articles::findFirst(["id = ?0", "bind" => [$params]]);

        $objToArray = $article->toArray();
        $objToArray['textdata']['text'] = $article->textdata->text;

        $this->view->obj = $objToArray;
    }

    public function saveAction($params = null)
    {
        if ($this->request->isPost())
        {
            if ($params) $article = Articles::findFirst(["id = ?0", "bind" => [$params]]);
            else $article = new Articles();

            // validate
            if (!$article -> getTrueType($this->request->getPost('type', 'alphanum')))
            {
                $this->flash->error($this->translate[ 'articles-validtype' ]);
                $this->view->disable();

                return $this->response->redirect('admin/articles');
            }

            $article->title = $this->request->getPost('title', 'striptags');
            $article->type = $this->request->getPost('type', 'striptags');
            $article->published = 1;
            $article->editdate = time();

            if (!$params)
            {
                $article->sortorder = 0;
                $article->views = 0;
                $article->publishdate = time();
                $article->textdata = (new ArticlesText()) -> setText($this->request->getPost('text'));
            }
            else
            {
                $article -> getTextdata() -> update(['text' => $this->request->getPost('text')]);
            }
            if (!$article->save())
            {
                $strMsg = '';
                foreach ($article->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }
            $this->response->redirect('admin/articles');
            $this->view->disable();

            return true;
        }

        $this -> view -> pageHeader = $this -> translate['articles-text'];
        $this -> view -> pageDesc = $this -> translate['articles-text_desc'];
    }

    public function deleteAction($params = null)
    {
        $article = Articles::findFirst(["id = ?0", "bind" => [$params]]);
        if ($article)
        {
            $this->flash->error($this->translate[ 'articles-deleted' ]);
            $article -> delete();
        }

        return $this->response->redirect('admin/articles');
    }
}
