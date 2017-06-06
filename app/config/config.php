<?php

return new \Phalcon\Config([
    'application' => [
        'controllersDir' => __DIR__ . '/../controllers/',
        'modelsDir' => __DIR__ . '/../models/',
        'viewsDir' => __DIR__ . '/../views/',
        'libraryDir' => __DIR__ . '/../library/',
        'vendorDir' => __DIR__ . '/../../vendor/',
        'cacheDir' => __DIR__ . '/../../var/cache/',
        'tmpDir' => __DIR__ . '/../../var/tmp/',
        'logDir' => __DIR__ . '/../../var/log/',
        'pubDir' => __DIR__ . '/../../public/',
        'incubatorDir' => __DIR__ . '/../library/Phalcon/',
    ],
    'phalcon' => [
        'controllersDir' => '../app/controllers/',
        'modelsDir' => __DIR__ . '/../models/',
        'viewsDir' => '../app/views/',
        'baseUri' => '/',
    ],
    'session' => [
        'cookieLifetime' => 604800,
        'redisLifetime' => 604800,
        'cookieName' => 'SessionId',
    ],
]);
