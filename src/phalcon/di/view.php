<?php
use Twig\Extra\Markdown\MarkdownExtension;
use Twig\Extra\Markdown\DefaultMarkdown;
use Twig\Extra\Markdown\MarkdownRuntime;
use Twig\RuntimeLoader\RuntimeLoaderInterface;


return function () {
    $config = $this->getConfig();
    $view = new Phalcon\Mvc\View();
    $view->setDI($this);
    $view->setViewsDir(APP_PATH . '/views/');
    $view->setMainView(null);

    $view->registerEngines([
        '.twig' => function($view, $di) use ($config) {
            $options = array(
                'debug'                 => $config->debug,
                'charset'               => 'UTF-8',
                'base_template_class'   => 'Twig_Template',
                'strict_variables'      => $config->debug,
                'autoescape'            => false,
                'cache'                 => false,//$config->debug ? false : (APP_PATH . '/../.cache/'.VERSION.'/'),
                'auto_reload'           => !$config->debug,
                'optimizations'         => -1,
            );
            $functions = [
                new Twig_SimpleFunction('json_encode', 'json_encode'),
            ];
            $twig = new \Phalcon\Mvc\View\Engine\Twig($view, $di, $options, $functions);

            $twig->getTwig()->addExtension(new Twig_Extension_Debug());

            $twig->getTwig()->addExtension(new MarkdownExtension());

            $twig->getTwig()->addRuntimeLoader(new class implements RuntimeLoaderInterface {
                public function load($class) {
                    if (MarkdownRuntime::class === $class) {
                        return new MarkdownRuntime(new DefaultMarkdown());
                    }
                }
            });
            return $twig;
        },
        '.html' => 'Phalcon\\Mvc\\View\\Engine\\Php'
    ]);

    return $view;
};