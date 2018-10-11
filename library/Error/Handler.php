<?php
/*
  +------------------------------------------------------------------------+
  | Phalcon Framework                                                      |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2015 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  |          Nikita Vershinin <endeveit@gmail.com>                         |
  |          Serghei Iakovlev <sadhooklay@gmail.com>                       |
  +------------------------------------------------------------------------+
*/
namespace App\Error;

use Phalcon\Di;
use Phalcon\Logger\Formatter;
use Main\Module;
use Phalcon\Mvc\View;

class Handler
{
    /**
     * Registers itself as error and exception handler.
     *
     * @return void
     */
    public static function register()
    {
        switch (APPLICATION_ENV)
        {
            case 'production':
            default:
                ini_set('display_errors', 0);
                error_reporting(0);
                break;
            case 'development':
                ini_set('display_errors', 1);
                error_reporting(-1);
                break;
        }

        set_error_handler(function ($errno, $errstr, $errfile, $errline)
        {
            if (!($errno & error_reporting()))
            {
                return;
            }

            $options = [
                'type'    => $errno,
                'message' => $errstr,
                'file'    => $errfile,
                'line'    => $errline,
                'isError' => true,
            ];

            static::handle(new Error($options));
        });

        set_exception_handler(function (\Exception $e)
        {
            $options = [
                'type'        => $e->getCode(),
                'message'     => $e->getMessage(),
                'file'        => $e->getFile(),
                'line'        => $e->getLine(),
                'isException' => true,
                'exception'   => $e,
            ];

            static::handle(new Error($options));
        });

        register_shutdown_function(function ()
        {
            if (!is_null($options = error_get_last()))
            {
                static::handle(new Error($options));
            }
        });
    }

    /**
     * Logs the error and dispatches an error controller.
     *
     * @param  \Phalcon\Error\Error $error
     * @return mixed
     */
    public static function handle(Error $error)
    {
        $di = Di::getDefault();
        $config = $di->getShared('config')->error;
        $type = static::getErrorType($error->type());
        $message = "$type: {$error->message()} in {$error->file()} on line {$error->line()}";
        $response = $di->getShared('response');

        if (isset($config->formatter) && $config->formatter instanceof Formatter)
        {
            $config->logger->setFormatter($config->formatter);
        }

        $config->logger->log($message);

        if ($error -> getValue('type') == 2002)
        {
            return $response->setContent('Nie można połączyć się z bazą danych. Skontaktuj się z administratorem pod adresem email: '.$di->getShared('config') -> mail -> fromEmail.'')->send();
        }

        switch ($error->type())
        {
            case E_WARNING:
            case E_NOTICE:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
            case E_USER_NOTICE:
            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
            case E_ALL:
                return false;
                break;
            case 0:
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                $view = static::renderError($error, $di, $config, $di->get('translate')[ 'some-error' ]);
                break;
            case 5:
                $view = static::renderError($error, $di, $config, $di->get('translate')[ '404-error' ]);
                break;
            default:
                $view = static::renderError($error, $di, $config, 'fatal');
                break;
        }

        return $response->setContent($view->getContent())->send();
    }

    /*
     * Function that render content
     */
    public static function renderError($error, $di, $config, $infodata)
    {
        if (!class_exists('Main\Module'))
        {
            include_once APPLICATION_PATH . '/modules/main/Module.php';
            $module = new Module();
            $module->registerServices($di);
            $module->registerAutoloaders($di);
        }

        $dispatcher = $di->getShared('dispatcher');
        if ($di->has('view')) $view = $di->getShared('view');
        else $view = new View();

        $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);

        $dispatcher->setNamespaceName($config->namespace);
        $dispatcher->setControllerName($config->controller);
        $dispatcher->setActionName($config->action);
        if ($infodata != 'fatal') $dispatcher->setParams(['error' => $error, 'naglerror' => $infodata]);

        $view->start();
        $dispatcher->dispatch();
        $view->render($config->controller, ($infodata == 'fatal' ? $config->fatalaction : $config->action), $dispatcher->getParams());
        $view->finish();

        return $view;
    }

    /**
     * Maps error code to a string.
     *
     * @param  integer $code
     * @return string
     */
    public static function getErrorType($code)
    {
        switch ($code)
        {
            case 0:
                return 'Uncaught exception';
            case E_ERROR:
                return 'E_ERROR';
            case E_WARNING:
                return 'E_WARNING';
            case E_PARSE:
                return 'E_PARSE';
            case E_NOTICE:
                return 'E_NOTICE';
            case E_CORE_ERROR:
                return 'E_CORE_ERROR';
            case E_CORE_WARNING:
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR:
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING:
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR:
                return 'E_USER_ERROR';
            case E_USER_WARNING:
                return 'E_USER_WARNING';
            case E_USER_NOTICE:
                return 'E_USER_NOTICE';
            case E_STRICT:
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR:
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED:
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED:
                return 'E_USER_DEPRECATED';
            case 5:
                return '404';
        }

        return $code;
    }
}
