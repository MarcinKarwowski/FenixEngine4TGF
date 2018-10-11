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

use Game\Library\EgoData;

class UpdateController extends ControllerBase
{
    /*
     * Check if there is some update files and send data in json
     */
    public function indexAction()
    {
        $this->view->disable();

        $arrData = ['engine' => 1, 'games' => []];
        $cdir = scandir(BASE_PATH . '/update/');
        foreach ($cdir as $key => $value)
        {
            if ($value != '..' && $value != '.')
            {
                if (file_exists(BASE_PATH . '/update/' . $value))
                {
                    $filename = explode('.', $value);
                    if (isset($filename[ 0 ]) && $filename[ 0 ] == 'engine')
                    {
                        $arrData[ 'engine' ] = $filename[ 1 ];
                    }
                    elseif (isset($filename[ 0 ]) && $filename[ 0 ] == 'game')
                    {
                        if (isset($filename[ 1 ]))
                        {
                            $arrData[ 'games' ][ $filename[ 1 ] ] = $filename[ 2 ];
                        }
                    }
                }
            }
        }
        echo json_encode($arrData);
    }

    /*
     * Get zipped file from serwer
     */
    public function getZipAction()
    {
        $code = $this->dispatcher->getParam('code');
        if ($this->config->game->publicUrl == 'e-fenix.info')
        {
            $cdir = scandir(BASE_PATH . '/update/');
            foreach ($cdir as $key => $value)
            {
                if ($value != '..' && $value != '.' && $value != 'index.php')
                {
                    if (file_exists(BASE_PATH . '/update/' . $value))
                    {
                        if (hash('sha256', $value) == $code) // we can send file
                        {
                            return $this->response->setFileToSend(BASE_PATH . '/update/' . $value);
                        }
                    }
                }
            }
        }
        $this->flash->error($this->translate[ 'update-cantopen' ]);

        return $this->response->redirect('');
    }

    /*
     * Making update files
     */
    public function prepareUpdateAction()
    {
        $code = $this->dispatcher->getParam('code');
        if ($code == '08f96b5f30f722489b1325c3144d0c3a50615a55acc0956315437cdaba84f1f2' && $this->config->game->publicUrl == 'e-fenix.info')
        {
            // remove old files
            $cdir = scandir(BASE_PATH . '/update/');
            foreach ($cdir as $key => $value)
            {
                if ($value != '..' && $value != '.' && $value != 'index.php' && $value != 'database')
                {
                    if (file_exists(BASE_PATH . '/update/' . $value))
                    {
                        unlink(BASE_PATH . '/update/' . $value);
                    }
                }
            }

            // Engine files
            $zip = new \ZipArchive();
            $filename = BASE_PATH . '/update/engine.' . str_replace('.', '-', $this->config->game->engineVer) . '.zip';

            if ($zip->open($filename, \ZipArchive::CREATE) !== TRUE)
            {
                $this->flash->error($this->translate[ 'update-cantopen' ] . ': ' . $filename);

                return $this->response->redirect('');
            }
            $this->addToZipDir(BASE_PATH . '/update/database/', $zip);
            $this->addToZipDir(BASE_PATH . '/library/', $zip);
            $this->addToZipDir(BASE_PATH . DIRECTORY_SEPARATOR . 'application/modules/admin/', $zip);
            $this->addToZipDir(BASE_PATH . DIRECTORY_SEPARATOR . 'application/modules/main/', $zip);
            $this->addToZipDir(BASE_PATH . DIRECTORY_SEPARATOR . 'application/templates/', $zip);
            $this->addToZipDir(BASE_PATH . DIRECTORY_SEPARATOR . 'public_html/assets/', $zip);

            $zip->close();

            // games files
            $cdir = scandir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'storage/');
            foreach ($cdir as $key => $value)
            {
                if ($value != '..' && $value != '.' && $value != 'index.php')
                {
                    if (is_dir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR))
                    {
                        if (file_exists(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . 'config.php'))
                        {
                            $gameconfig = include(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . 'config.php');

                            $zip = new \ZipArchive();
                            $filename = BASE_PATH . '/update/game.' . $value . '.' . str_replace('.', '-', $gameconfig->version) . '.zip';

                            if ($zip->open($filename, \ZipArchive::CREATE) !== TRUE)
                            {
                                $this->flash->error($this->translate[ 'update-cantopen' ] . ': ' . $filename);

                                return $this->response->redirect('');
                            }
                            $this->addToZipDir(BASE_PATH . DIRECTORY_SEPARATOR . 'application/storage/' . $value . '/', $zip);
                            $zip->close();
                        }
                    }
                }
            }

            $this->flash->success($this->translate[ 'update-success' ]);

            return $this->response->redirect('admin/check-update');
        }
        else
        {
            $this->flash->error($this->translate[ 'acl-noaccess' ]);

            return $this->response->redirect('');
        }
    }

    private function addToZipDir($dirPath, $zip)
    {
        $files = new \DirectoryIterator($dirPath);
        foreach ($files as $file)
        {
            if ($file->isDot())
            {
                continue;
            }
            if ($file->isDir())
            {
                self::addToZipDir($file->getPathname(), $zip);
            }
            else
            {
                $zip->addFile($file->getPathname(), str_replace(BASE_PATH . DIRECTORY_SEPARATOR, '', $file->getPathname()));
            }
        }
    }
}
