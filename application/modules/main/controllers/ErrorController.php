<?php


namespace Main\Controller;

class ErrorController extends ControllerBase
{
    public function indexAction()
    {
        $error = $this->dispatcher->getParam('error');

        switch ($error->code()) {
            case 404:
                $code = 404;
                break;
            default:
                $code = 500;
        }

        $this->getDi()->getShared('response')->resetHeaders()->setStatusCode($code, null);
        $this->view->error = $error;
        $this->view->naglerror = $this->dispatcher->getParam('naglerror');
    }
    public function fatalAction() {
        $this->getDi()->getShared('response')->resetHeaders()->setStatusCode(500, null);
    }
}
