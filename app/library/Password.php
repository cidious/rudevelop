<?php
namespace Library;

use Phalcon\Mvc\User\Component;

class Password extends Component
{
    private static $saltSize = 8;

    /**
     * генерит соль, шифрует пароль, возвращает шифрованный пароль и соль
     * @static
     * @param string
     * @return array
     */
    public static function cypherPasswd(string $passwd)
    {
        $salt             = self::shortId(self::$saltSize);
        $passwdCryptCycle = mt_rand(1, 10);
        for ($ix = 0; $ix < $passwdCryptCycle; $ix++) {
            $passwd = openssl_digest($salt . $passwd, "sha512");
        }
        $salt .= $passwdCryptCycle;

        return array($passwd, $salt);
    }

    /**
     * возвращает случайную строку из заданных символов;
     * используется при генерации соли
     * @param int $length
     * @return string
     */
    public static function shortId(int $length = 3)
    {
        $characters =
            '123456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ_-';
        $random_id  = "";
        $tries      = 100;

        while (1) {
            for ($ix = 0; $ix < $tries; $ix++) {
                for ($i = 0; $length > $i; $i++) {
                    $random_id .= $characters[mt_rand(0, strlen($characters) - 1)];
                }

                return $random_id;
            }
            $length++;
        }
    }

    /**
     * сравнивает данный пароль с сохраненным паролем
     * @param string $userSalt
     * @param string $userPasswd
     * @param string $givenPasswd
     * @return bool
     */
    public static function checkPassword(string $userSalt, string $userPasswd, string $givenPasswd)
    {
        // вычисляем соль
        $salt = substr($userSalt, 0, self::$saltSize);
        // вычисляем циклы
        $passwdCryptCycle = (int)substr($userSalt, self::$saltSize);

        $passwd = $givenPasswd;
        for ($ix = 0; $ix < $passwdCryptCycle; $ix++) {
            $passwd = openssl_digest($salt . $passwd, "sha512");
        }

        // пароль не совпал
        if ($passwd != $userPasswd) {
            return false;
        }

        return true;
    }

}
