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

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        return $this->response->redirect((isset($this -> config -> game -> params -> defaultPage) ? $this -> config -> game -> params -> defaultPage : '/game'));
    }
}
