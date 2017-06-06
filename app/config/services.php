<?php

use Phalcon\DI\FactoryDefault, Phalcon\Mvc\View, Phalcon\Mvc\Url as UrlResolver,
    Phalcon\Mvc\View\Engine\Volt as VoltEngine,
    Phalcon\Mvc\Dispatcher;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * Register the global configuration as config
 */
$di->set('config', $config);

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->site->baseUri);

    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {
    $view = new View();
    $view->setViewsDir($config->application->viewsDir);
    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {
            $config->site->DEBUG ? $volt_debug = true : $volt_debug = false;

            $volt = new VoltEngine($view, $di);
            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_',
                'compileAlways' => $volt_debug,
            ));

            $compiler = $volt->getCompiler();
            $compiler->addFunction('round', 'round');
            $compiler->addFunction('unserialize', 'unserialize');
            $compiler->addFunction('implode', 'implode');
            $compiler->addFunction('nl2br', 'nl2br');
            $compiler->addFunction('mb_substr', 'mb_substr');
            $compiler->addFunction('print_r', 'print_r');
            $compiler->addFunction('url_encode', 'urlencode');
            $compiler->addFunction('count', 'count');

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

$di->set('db', function () use ($config) {
    //ini_set('phalcon.orm.cast_on_hydrate', 1);
    return new \Phalcon\Db\Adapter\Pdo\Mysql([
        'host' => $config->db->host,
        'port' => $config->db->port,
        'dbname' => $config->db->dbname,
        'username' => $config->db->username,
        'password' => $config->db->password,
        'options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8,time_zone='+0:00'",
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => 1,
        ],
    ]);
}, true);

$di->set('dispatcher', function () use ($di) {
    $eventsManager = $di->getShared('eventsManager');
    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('Controllers');
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});

$di->set('router', function () {
    return require __DIR__ . '/routes.php';
});
