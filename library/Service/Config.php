<?php

namespace App\Service;

class Config extends \Phalcon\Mvc\User\Component
{
    public static function save($arrNewConfig = [], $removeParams = false)
    {
        if (!is_file(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'parameters.php'))
        {
            copy(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'parameters_dist.php', APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'parameters.php');
        }
        $arrConfig = include(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'parameters.php');

        if ($removeParams)
        {
            $arrConfig['game']['params'] = [];
        }

        // merge configs
        $arrConfig = array_replace_recursive($arrConfig, $arrNewConfig);

        file_put_contents(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'parameters.php', '<?php
            return ' . var_export($arrConfig, true) . ';
            ');
    }
}
