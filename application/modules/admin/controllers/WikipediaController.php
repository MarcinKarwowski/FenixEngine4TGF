<?php

namespace Admin\Controller;

use Main\Models\Wikipedia;
use Main\Models\WikipediaText;

class WikipediaController extends ControllerBase
{
    /**
     * Dashboard
     */
    public function indexAction()
    {
        // template
        $this->view->pageHeader = $this->translate[ 'wikipedia-text' ];
        $this->view->pageDesc = $this->translate[ 'wikipedia-text_desc' ];
    }

    public function editAction($params = null)
    {
        $this->view->modalTitle = $this->translate[ 'articles-edit' ];

        $article = Wikipedia::findFirst(["id = ?0", "bind" => [$params]]);

        $objToArray = $article->toArray();
        $objToArray['wikitext']['text'] = $article->wikitext->text;

        $this->view->obj = $objToArray;
    }

    public function saveAction($params = null)
    {
        if ($this->request->isPost())
        {
            if ($params) $article = Wikipedia::findFirst(["id = ?0", "bind" => [$params]]);
            else $article = new Wikipedia();

            $article->title = $this->request->getPost('title', 'striptags');
            $article->type = $this->request->getPost('type', 'striptags');
            $article->published = 1;
            $article->editdate = time();
            $article->publishdate = time();

            if (!$params)
            {
                $article->sortorder = 0;
                $article->views = 0;
                $article->wikitext = (new WikipediaText())->setText($this->request->getPost('text'));
            }
            else
            {
                $article->getWikitext()->update(['text' => $this->request->getPost('text')]);
            }

            $parent = Wikipedia::findFirst($this->request->getPost('parent', 'int'));
            if (isset($parent -> id))
            {
                $article -> parent_id = $parent -> id;
            }
            else $article -> parent_id = 0;

            if (!$article->save())
            {
                $strMsg = '';
                foreach ($article->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }

            $this->view->disable();

            return $this->response->redirect('admin/wikipedia');
        }

        $this->view->pageHeader = $this->translate[ 'wikipedia-text' ];
        $this->view->pageDesc = $this->translate[ 'wikipedia-text_desc' ];
    }

    public function deleteAction($params = null)
    {
        $article = Wikipedia::findFirst(["id = ?0", "bind" => [$params]]);
        if (isset($article->id))
        {
            $this->flash->error($this->translate[ 'articles-deleted' ]);
            $article->delete();
        }

        return $this->response->redirect('admin/wikipedia');
    }
}
