<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces(array(
    'Controllers' => $config->application->controllersDir,
    'Models' => $config->application->modelsDir,
    'Library' => $config->application->libraryDir,
    'Phalcon' => $config->application->incubatorDir,
))->register();
