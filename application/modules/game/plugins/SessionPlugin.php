<?php

namespace Game\Plugins;

use Main\Models\Characters;

class SessionPlugin extends \Phalcon\Mvc\User\Component
{
    public $component = 'session';

    public function afterLogin($event, $myComponent)
    {
        // delete cache of online users list
        $this->modelsCache->delete("user_online.cache");
    }
}

return new SessionPlugin();