<?php

namespace App\Service;

use Main\Models\Notifications;
use Main\Models\CharactersNotifications;

class Notify extends \Phalcon\Mvc\User\Component
{
    public static function send($arrNotify = [])
    {
        $notify = new Notifications();
        $notify->title = $arrNotify['title'];
        $notify->text = $arrNotify['text'];
        $notify->type = $arrNotify['type'];
        $notify->popup = $arrNotify['popup'];
        $notify->globals = $arrNotify['globals'];
        $notify->item_id = (isset($arrNotify['item_id']) ? $arrNotify['item_id'] : 0);
        $notify->notifyinfo = (new CharactersNotifications())->setNotificationId($notify->id)->setCharacterId($arrNotify['character_id'])->setReaded(0)->setExpiry(null);
        if ($notify->save()) return true;
        else return false;
    }

}