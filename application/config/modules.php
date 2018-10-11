<?php

/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2015 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | File get list of modules                                               |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

$result = array();

$cdir = scandir(APPLICATION_PATH . '/modules/');
foreach ($cdir as $key => $value) {
    if ($value != '..' && $value != '.') {
        if (is_dir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules/' . $value . DIRECTORY_SEPARATOR)) {
            if (file_exists(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules/' . $value . DIRECTORY_SEPARATOR . 'Module.php')) {
                $result[$value] = array(
                    'className' => ucfirst($value) . '\Module',
                    'path' => APPLICATION_PATH . '/modules/' . $value . '/Module.php'
                );
            }
        }
    }
}

return (array)$result;

