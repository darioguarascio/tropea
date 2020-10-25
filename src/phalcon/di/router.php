<?php

$router = new \Phalcon\Mvc\Router(false); // Here tell the router to not using default routes 
$router->setDI($di);
$router->setUriSource(Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);

foreach (conf()->routes as $category => $list) {
    foreach ($list as $name => $route) {
        $router
            ->add(
                $route->pattern,
                $route->controller,
                $route->get('methods', ['GET']),
                $route->get('position', Phalcon\Mvc\Router::POSITION_FIRST) )
            ->setName($name);
    }
}

$router->notFound([ 'controller' => 'Error', 'action' => 'http404' ]);
$router->setDefaults([ 'controller' => 'Error', 'action' => 'http404' ]);

return $router;
