<?php
namespace App\Service;

use Phalcon\Mvc\User\Component;

/**
 * App\Service\Mail
 * Sends e-mails based on pre-defined templates
 */
class Update extends Component
{

    public $updateurl = 'http://e-fenix.info/update';
    public $updategeturl = 'http://e-fenix.info/get-update/';

    /**
     * get update info
     */
    public function checkUpdate()
    {
        $result = file_get_contents($this -> updateurl);
        if ($result)
        {
            return json_decode($result, true);
        }
        else return false;
    }
}
