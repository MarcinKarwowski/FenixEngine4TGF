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

class ResetController extends ControllerBase
{
    private $lastreset;

    public function indexAction()
    {
        $this->view->disable();

        if ($this -> timeCheck() === true)
        {
            /*
             * Minute reset - everything here
             */
            $this->modelsManager->executeQuery("UPDATE Main\Models\Characters SET hp=hp+1 WHERE hp<max_hp");
            /*
             * Five minute reset
             */
            if (date('i', time()) % 5 == 0) $this -> five();
            /*
             * EGO data reset
             */
            (new EgoData()) -> getReset();
        }

        echo 'Reset done';
    }

    private function timeCheck()
    {
        $file = $this -> config -> cache -> url.'reset.php';
        if (is_file($file))
        {
            $this -> lastreset = (int)file_get_contents($file);
            if ($this -> lastreset + 50 < time())
            {
                file_put_contents($file, time());
                return true;
            }
            else return false;
        }
        else
        {
            file_put_contents($file, time());
            return true;
        }
    }

    /*
     * Method run every 5 min
     */
    private function five()
    {

    }
}
