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

use Game\Library\EgoData;
use Game\Models\Locations;

class LocationController extends ControllerBase
{

    /*
     * Load map data
     */
    public function coordsAction()
    {
        $this -> view -> coords = Locations::find(['conditions' => "coords!='0'", 'columns' => 'id, name, type, coords']) -> toArray();
    }

    /*
     * Show location page
     */
    public function showAction($id = null)
    {
        $user = $this->auth->getUser();

        $location = Locations::findFirst(['id = ?0', 'bind' => $id]);
        if (!$location->id)
        {
            $this->flash->error($this->translate[ 'locations-no_loc' ]);
            return $this->response->redirect('game/chat');
        }

        // Update user location
        if ($user -> character -> location_id != $id && $location -> type == 'LOCATION') $user -> character -> update(['location_id' => $id]);

        $this -> view -> location = $location;
        //$this -> view -> renderview = 1;
    }

    /*
 * Show location page in window
 */
    public function windowAction($id = null)
    {
        $user = $this->auth->getUser();

        $location = Locations::findFirst(['id = ?0', 'bind' => $id]);
        if (!$location->id)
        {
            $this->flash->error($this->translate[ 'locations-no_loc' ]);
            return $this->response->redirect('game/chat');
        }

        $this -> view -> location = $location;
        $this -> view -> renderview = 1;
    }
}
