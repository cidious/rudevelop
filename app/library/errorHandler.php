<?php

namespace Library\Helper;

use Phalcon\Mvc\User\Component;

class errorHandler extends Component
{
    static private $class, $message, $trace, $auth;

    static public function handle(\Exception $e)
    {
        $config = \Phalcon\DI::getDefault()->getConfig();
        $session = \Phalcon\DI::getDefault()->getSession();
        // class could be: Phalcon\Exception PDOException
        self::$class = get_class($e);
        self::$message = $e->getMessage();
        self::$trace = $e->getTrace();
        self::$auth = $session->get('auth');

        if ($config->site->DEBUG) {
            self::output();
        } else {

        }
    }

    static private function output()
    {
        echo '<small>user: '.self::$auth['login'].'</small><br>';
        echo '<p><b>'.self::$class.':</b></p> '.self::$message;
        echo '<p><b>Trace:</b></p><p><pre>';
        foreach (self::$trace as $tr) {
            if (isset($tr['file'])) {
                echo "{$tr['file']}:{$tr['line']} {$tr['function']}()\n";
            }
        }
        echo '</pre></p>';
    }
}