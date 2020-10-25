<?php
return function () {

    $url = new Phalcon\Mvc\Url();
    $url->setBaseUri('/');

    return $url;
};