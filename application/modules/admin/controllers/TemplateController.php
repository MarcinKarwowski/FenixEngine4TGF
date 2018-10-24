<?php

namespace Admin\Controller;

use App\Service\ParseString;
use Admin\Forms\TemplateConfigureForm;
use App\Service\File,
    App\Service\Config;

class TemplateController extends ControllerBase
{
    /**
     * Dashboard
     */
    public function indexAction()
    {
        // template
        $this -> view -> pageHeader = $this -> translate['template-info'];
        $this -> view -> pageDesc = '';


        /*
         * Show game configure form
         */
        $form = new TemplateConfigureForm;
        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                // save config file
                Config::save(
                    array('game' => array(
                        'custom' => htmlentities($this->request->getPost('custom')),
                        'template' => htmlentities($this->request->getPost('template')),
                        'template_text_color' => htmlentities($this->request->getPost('template_text_color'))
                    ),
                    )
                );

                $this->flash->success($this->translate['configuration-success']);

                $this->response->redirect('/admin/template');
                $this->view->disable();

                return;
            }
        }

        $this->forms->set('templateconfigure', $form);
    }

    public function uploadAction($params = null)
    {
        $this->view->disable();

        if ($this->request->hasFiles() == true) {
            foreach ($this->request->getUploadedFiles() as $file) {
                // 5 MB
                if ($file->getSize() > 5000000) {
                    $this->flash->error($this->translate['account-file_to_big']);

                    return $this->response->redirect('admin/template/index');
                }
                if (!File::imageCheck($file->getRealType()) || !in_array($file->getExtension(), ['jpg', 'png', 'gif'])) {
                    $this->flash->error($this->translate['account-file_wrong_format']);

                    return $this->response->redirect('admin/template/index');
                }
                // image size
                list($width, $height, $type, $attr) = getimagesize($file->getTempName());
                if ($width < 170 || $height < 200) {
                    $this->flash->error($this->translate['account-file_wrong_size'] . ' ' . $width . 'x' . $height . '');
                    return $this->response->redirect('admin/template/index');
                }

                $newFileName = md5(uniqid(rand(), true)) . '.' . $file->getExtension();
                $file->moveTo(PUBLIC_PATH . $this->config->url->staticBaseUri . 'static/' . $newFileName);

                // save config file
                Config::save(
                    array('game' => array(
                        'template_bg' => $this->config->url->staticBaseUri. 'static/' . $newFileName
                    ),
                    )
                );
                $this->flash->success($this -> translate['template-bg_success']);
                return $this->response->redirect('admin/template/index');
            }
        } else {
            $this->flash->error($this->translate['account-file_no_file']);
            return $this->response->redirect('admin/template/index');
        }
    }
}
