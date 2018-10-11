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

use App\Service\File;

class AccountController extends ControllerBase
{
    public function indexAction()
    {
        $user = $this->auth->getUser();

        $this->view->registerdate = date('d-m-Y', $user->registerdate);
        $this->view->charcount = $user->characters->count();

    }

    public function avatarAction()
    {
        $this->view->avatarPath = $this->auth->getUser()->character->getAvatar();
    }

    public function uploadAction()
    {
        $this->view->disable();

        if ($this->request->hasFiles() == true) {
            foreach ($this->request->getUploadedFiles() as $file) {
                // 500 KB
                if ($file->getSize() > 500000) {
                    $this->flash->error($this -> translate['account-file_to_big']);
                    $this->response->redirect('game/account/avatar');

                    return false;
                }
                if (!File::imageCheck($file->getRealType()) || !in_array($file->getExtension(), ['jpg', 'png', 'gif'])) {
                    $this->flash->error($this -> translate['account-file_wrong_format']);

                    return $this->response->redirect('game/account/avatar');
                }
                // image size
                list($width, $height, $type, $attr) = getimagesize($file->getTempName());
                if ($width < 170 || $height < 100)
                {
                    $this->flash->error($this -> translate['account-file_wrong_size'].' '.$width.'x'.$height.'');
                    $this->response->redirect('game/account/avatar');

                    return false;
                }

                $newAvatarName = md5(uniqid(rand(), true)) . '.' . $file->getExtension();
                $file->moveTo(PUBLIC_PATH.$this->config->url->staticBaseUri . 'static/avatars/' . $newAvatarName);
            }
            if (isset($newAvatarName))
            {

                // Update model
                $user = $this->auth->getUser();

                $avfile = $user -> character -> getAvatar();
                // remove old avatar
                if (is_file(PUBLIC_PATH.$avfile) && $user -> character -> avatar != ''){
                    unlink(PUBLIC_PATH.$avfile);
                }
                $user -> character->save(['avatar' => $newAvatarName]);

                $this->flash->success($this -> translate['account-file_succes']);
                $this->response->redirect('game/account/avatar');
                return false;
            }
            else{
                $this->flash->error($this -> translate['account-file_no_file']);
                $this->response->redirect('game/account/avatar');
                return false;
            }

        } else {
            $this->flash->error($this -> translate['account-file_no_file']);
            $this->response->redirect('game/account/avatar');
            return false;
        }
    }
}
