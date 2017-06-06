<?php
try {

    /*
     * читаем конфигурацию, всё содержится в config.php;
     * хотя обычно выделяется site-config.ini содержащий настройки,
     * специфические для данного сайта
     */
    $config = include __DIR__ . "/../app/config/config.php";
    $configS = new \Phalcon\Config\Adapter\Ini(__DIR__."/../site-config.ini");
    $config->merge($configS);

    /* авто-загрузчик для Фалкона */
    include __DIR__ . "/../app/config/loader.php";

    /* подключаем службы Фалкона */
    include __DIR__ . "/../app/config/services.php";

    /* обрабатываем запрос */
    $application = new \Phalcon\Mvc\Application();
    $application->setDI($di);
    echo $application->handle()->getContent();

} catch (\Throwable $e) {
    echo '<pre>';
    echo $e->getMessage();
    foreach ($e->getTrace() as $trace) {
        if (isset($tr['file'])) {
            echo "{$tr['file']}:{$tr['line']} {$tr['function']}()\n";
        }
    }
    echo '</pre></p>';
}