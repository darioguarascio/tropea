<?php
return function () {
    $locale = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    if (!$locale) {
        $locale = 'en_US';
    }
    return $locale;
};