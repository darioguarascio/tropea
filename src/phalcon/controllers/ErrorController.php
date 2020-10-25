<?php

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Controller;

class ErrorController extends OutputController
{
    private $httpError = 500;

    public function beforeExecuteRoute(Dispatcher $dispatcher) {
    }

    public function afterExecuteRoute(Dispatcher $dispatcher) {
        return false;
    }

    public function http410Action() : array {
        $this->httpError = 401;
        $this->response->setHeader('WWW-Authenticate', 'Basic realm=Secured');
        $this->response->setContent('Unauth');
        return [];
    }

    public function http404Action() : array {
        $this->httpError = 404;
        $this->response->setContent('404');
        $this->view->setMainView('errors/404');
        return [];
    }

    public function http500Action() : array {
        $this->httpError = 500;
        return [];
    }
}
