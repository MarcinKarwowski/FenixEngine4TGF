<?php

namespace Game\Library;

use Game\Models\CharactersAbilities;
use Game\Models\CharactersMats;
use Game\Models\Locations;
use Game\Models\Actions;
use Game\Models\Mats;
use Main\Models\CharactersNotifications;
use Main\Models\Notifications;
use Main\Models\Users;

/**
 * Class BasicData
 * @package Game\Library
 */
class EgoData extends \Phalcon\Mvc\User\Component
{
    public static function nextLevel($level = 1)
    {
        return ceil(2*pow($level, 3)+20*pow($level, 2)+(3*$level)+175);
    }
    /*
     * In game data
     * $params = array('controller')
     */
    public function getRefresh($params, Users $user)
    {
        $data = [];

        // online list
        $usersonline = $this->modelsCache->get('user_online.cache', 240);
        if ($usersonline === null)
        {
            $usersonline = [];
            foreach (Users::find(['conditions' => 'lpv > ' . (time() - 300), 'order' => 'id ASC']) as $oneuser)
            {
                if (isset($oneuser->character->id))
                {
                    $char = $oneuser->character;
                    if ($char->id) $usersonline[ $oneuser->id ] = ['id' => $char->id, 'name' => explode(' ', trim($char->name))[ 0 ], 'avatar' => $char->getAvatar(), 'acc' => $oneuser->id];
                }
            }
            $this->modelsCache->save('user_online.cache', $usersonline, 240);
        }
        $data['charsonline'] = $usersonline;


        return $data;
    }

    /*
     * Reset ego data
     */
    public function getReset()
    {
        // Location loses after daily reset
        if (date('i', time()) == '00' && date('H', time()) == '00')
        {

        }
    }
}