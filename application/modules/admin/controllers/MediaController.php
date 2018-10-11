<?php

namespace Admin\Controller;

use App\Service\File;
use Main\Models\ArticlesText;
use Main\Models\Media;

class MediaController extends ControllerBase
{
    /**
     * Dashboard
     */
    public function indexAction()
    {
        // template
        $this -> view -> pageHeader = $this -> translate['media-text'];
        $this -> view -> pageDesc = $this -> translate['media-text_desc'];
    }

    public function editAction($params = null)
    {
        $this->view->modalTitle = $this->translate[ 'media-edit' ];

        $file = Media::findFirst(["id = ?0", "bind" => [$params]]);
        if ($params)
        {
            $file -> textdata -> text;
        }

        $this->view->obj = $file;
    }

    public function saveAction($params = null)
    {
        if ($this->request->isPost())
        {
            if ($params) $mediafile = Media::findFirst(["id = ?0", "bind" => [$params]]);
            else $mediafile = new Media();

            // validate
            if (!$mediafile -> getTrueType($this->request->getPost('type', 'alphanum')))
            {
                $this->flash->error($this->translate[ 'articles-validtype' ]);
                $this->view->disable();

                return $this->response->redirect('admin/media');
            }
            $fileurl = null;
            if ($this->request->hasFiles()) {
                foreach ($this->request->getUploadedFiles() as $file) {
                    if (File::imageCheck($file->getRealType())) {
                        $fileurl = $this->config->url->staticBaseUri.'media/'.$mediafile->id.'.'.$file->getExtension();
                        $file->moveTo(PUBLIC_PATH.$this->config->url->staticBaseUri.'media/'.$mediafile->id.'.'.$file->getExtension());
                    } else {
                        $this->flash->error($this->translate[ 'media-wrong_type' ]);
                        return $this->response->redirect('admin/media');
                    }
                }
            }

            $mediafile->title = $this->request->getPost('title', 'striptags');
            $mediafile->type = $this->request->getPost('type', 'striptags');
            if ($fileurl) $mediafile->url = $fileurl;

            if (!$mediafile->save())
            {
                $strMsg = '';
                foreach ($mediafile->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }
            $this->view->disable();

            return $this->response->redirect('admin/media');
        }

        $this -> view -> pageHeader = $this -> translate['media-text'];
        $this -> view -> pageDesc = $this -> translate['media-text_desc'];
    }
}
