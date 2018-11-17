<?php

define('MICROTIME', microtime());
// session fix
ini_set('session.save_handler', 'files');
ini_set('opcache.enable', false);

// dev - comment when end of dev
putenv('APPLICATION_ENV=development');

define('BASE_PATH', dirname(__FILE__));
define('APPLICATION_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'application');
define('PUBLIC_PATH', dirname(__FILE__));
define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production');

require_once BASE_PATH . "/vendor/autoload.php";
/* Init correct config file */
if (is_file(APPLICATION_PATH."/config/parameters.php")) $config = include APPLICATION_PATH . "/config/core.php";
else $config = include APPLICATION_PATH . "/config/install_core.php";

$application = new \Phalcony\Application(APPLICATION_ENV, $config);
$application->bootstrap()->run();
