<?php

/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2016 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

use Phalcon\Mvc\Router;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use App\Error\Handler as ErrorHandler;
use App\Exception;
use Phalcon\Cache\Backend\File as BackFile;
use Phalcon\Cache\Frontend\Data as FrontData;

date_default_timezone_set('Europe/Warsaw');
setlocale(LC_ALL, 'pl_PL', 'pl', 'Polish_Poland.28592');

if (PHP_VERSION_ID < 50600) {
    iconv_set_encoding('internal_encoding', 'UTF-8');
}

if (APPLICATION_ENV == 'development')
{
    $debug = new \Phalcon\Debug();
    $debug->listen();
}
//ErrorHandler::register();

// Main params for config
$parameters = include_once __DIR__ . '/parameters_dist.php';
// Additional params for config
$parameters['cache'] = ['url' => APPLICATION_PATH.'/cache/'];
$parameters['logs'] = ['url' => APPLICATION_PATH.'/logs/'];
$parameters['error'] = [
    'logger' => new \Phalcon\Logger\Adapter\File(APPLICATION_PATH . '/logs/' . APPLICATION_ENV . '.log'),
    'formatter' => new \Phalcon\Logger\Formatter\Line('[%date%][%type%] %message%', 'Y-m-d H:i:s O'),
    'namespace' => 'Main\Controller',
    'controller' => 'error',
    'action' => 'index',
    'fatalaction' => 'fatal',
];

/*
 * Main config for App
 */
return array(
    'parameters' => &$parameters,
    'services' => array(
        'config' => array(
            'class' => function () use ($parameters) {
                return new \Phalcon\Config($parameters);
            }
        ),
        'logger' => array(
            'class' => '\Phalcon\Logger\Adapter\File',
            'shared' => true,
            '__construct' => array(
                $parameters['logs']['url'] . APPLICATION_ENV . '.log'
            )
        ),
        'profiler' => array(
            'class' => '\Phalcon\Db\Profiler',
            'shared' => true,
        ),
        'translate' => array(
            'class' => function ($application) use ($parameters) {

                $language = 'pl';
                $messages = [];
                // merge all translate files
                foreach ($application->getModules() as $key => $module) {
                    // Main translate file
                    if (file_exists(APPLICATION_PATH . "/modules/" . $key . "/lang/" . $language . ".php")) {
                        $messages = $messages + include APPLICATION_PATH . "/modules/" . $key . "/lang/" . $language . ".php";
                    }
                }

                return new \Phalcon\Translate\Adapter\NativeArray(array(
                    "content" => $messages
                ));
            }
        ),
        'url' => array(
            'class' => '\Phalcon\Mvc\Url',
            'shared' => true,
            'parameters' => $parameters['url']
        ),
        'tag' => array(
            'class' => '\App\Tag'
        ),
        'modelsMetadata' => array(
            'class' => function () use ($parameters) {
                if (APPLICATION_ENV == 'development') $metaData = new \Phalcon\Mvc\Model\MetaData\Memory();
                else $metaData = new \Phalcon\Mvc\Model\MetaData\Files(array('metaDataDir' => $parameters['cache']['url'].'metadata/'));

                return $metaData;
            }
        ),
        'modelsCache' => array(
            'class' => function () use ($parameters) {
                // Cache the files for 2 days using a Data frontend
                $frontCache = new FrontData(
                    array(
                        "lifetime" => 172800
                    )
                );
                $cache = new BackFile(
                    $frontCache,
                    array(
                        "cacheDir" => $parameters['cache']['url']
                    )
                );

                return $cache;
            }
        ),
        'dispatcher' => array(
            'class' => function ($application) {
                $di = $application -> getDi();
                $evManager = $di->getShared('eventsManager');

                $evManager->attach('dispatch:afterExecuteRoute', function($event, $dispatcher, $exception) use ($di){
                    // script time meter
                    list($a_dec, $a_sec) = explode(' ', MICROTIME);
                    list($b_dec, $b_sec) = explode(' ', microtime());
                    $duration = sprintf("%0.5f", $b_sec - $a_sec + $b_dec - $a_dec);
                    $di->get('view') -> setVars(['scriptTime' => $duration]);

                    // ajax reponse meter
                    if ($di->get('request')->isAjax()) {

                        // If there is a view file in ajax route we want to parse it into response
                        if ($di->get('view')->exists($dispatcher->getControllerName().'/'.$dispatcher->getActionName()))
                        {
                            $di->get('view')->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
                        }
                        else
                        {
                            $di->get('view')->disableLevel(array(
                                View::LEVEL_ACTION_VIEW => false,
                                View::LEVEL_LAYOUT => true,
                                View::LEVEL_MAIN_LAYOUT => true,
                                View::LEVEL_AFTER_TEMPLATE => true,
                                View::LEVEL_BEFORE_TEMPLATE => true
                            ));
                        }
                        $di->get('response')->setContentType('application/json', 'UTF-8');

                        /* hook to afterRender event */
                        if (null == $di->get('view')->getEventsManager()){
                            $eventManager = new \Phalcon\Events\Manager();
                            $di->get('view')->setEventsManager($eventManager);
                        }
                        else {
                            $eventManager = $di->get('view')->getEventsManager();
                        }
                        $eventManager->attach("view:afterRender", function ($event, $view) use ($di){

                            if (isset($view->getParamsToView()['renderview'])) $result = $view->getContent();
                            else $result = $view->getParamsToView();

                            $view->setContent(json_encode(["contents" => $result, 'ajax' => (isset($view->getParamsToView()['toAjax']) ? $view->getParamsToView()['toAjax'] : [])]));
                        });
                    }
                });

                $dispatcher = new \Phalcon\Mvc\Dispatcher();
                $dispatcher->setEventsManager($evManager);
                return $dispatcher;
            }
        ),
        'modelsManager' => array(
            'class' => function ($application) {
                //$eventsManager = $application->getDI()->get('eventsManager');
                $modelsManager = new \Phalcon\Mvc\Model\Manager();
                //$modelsManager->setEventsManager($eventsManager);
                //$eventsManager->attach('modelsManager', new \Engine\Db\Model\Annotations\Initializer());
                return $modelsManager;
            }
        ),
        'router' => array(
            'class' => function ($application) {
                $router = new Router(false);

                $router->add('/', array(
                    'module' => 'install',
                    'controller' => 'index',
                    'action' => 'index'
                ));
                $router->notFound(array(
                    'module' => 'install',
                    'namespace' => 'Install\Controller',
                    'controller' => 'index',
                    'action' => 'index',
                ));


                return $router;
            },
            'parameters' => array(
                'uriSource' => Router::URI_SOURCE_SERVER_REQUEST_URI
            )
        ),
        'view' => array(
            'class' => function ($application) use ($parameters) {
                $di = $application->getDI();

                //Create an event manager
                $eventsManager = $di->get('eventsManager');

                //Attach a listener for type "view"
                $eventsManager->attach("view", function ($event, $view) {
                    if ($event->getType() == 'notFoundView') {
                        throw new Exception('View not found ' . $view->getActiveRenderPath());
                    }
                });

                $view = new View();

                $view->registerEngines(array(
                    '.volt' => function ($view, $di) use ($parameters) {

                        $volt = new VoltEngine($view, $di);

                        $volt->setOptions(array(
                            'compiledPath' => $parameters['cache']['url'],
                            'compiledSeparator' => '_',
                            'stat' => true,
                            'compileAlways' => true
                        ));
                        // add global functions
                        $volt->getCompiler()->addFunction('html_entity_decode', 'html_entity_decode');

                        return $volt;
                    }
                ));
                // add global translate data
                $view->setVar("t", $di->get('translate'));

                //Bind the eventsManager to the view component
                $view->setEventsManager($eventsManager);

                return $view;
            },
            'parameters' => array(
                'layoutsDir' => APPLICATION_PATH . '/templates/layouts/'
            )
        ),
        'flash' => array(
            'class' => function () {
                return new \App\Service\MyFlash(array(
                    'error' => 'messages bg-danger text-danger alert alert-danger alert-dismissible',
                    'success' => 'messages bg-success text-success alert alert-success alert-dismissible',
                    'notice' => 'messages bg-info text-info alert alert-warning alert-dismissible'
                ));
            }
        ),
        'forms' => array(
            'class' => '\Phalcon\Forms\Manager'
        ),
        'mail' => array(
            'class' => '\App\Service\Mail'
        )
    ),
    'application' => array(
        'modules' => [
            'install' => [
                'className' => 'Install\Module',
                'path' => APPLICATION_PATH . '/modules/install/Module.php'
            ]
        ]
    ),
);
