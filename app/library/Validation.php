<?php
namespace Library;

use Phalcon\Mvc\User\Component;
use Models\User;

class Validation extends Component
{
    private $myEmail = 'd@cidious.com';

    /**
     * валидирует любое поле
     * @param string $field
     * @param string $value
     * @return array
     */
    public function validateAny(string $field, string $value)
    {
        try {
            switch ($field) {
                case 'username':
                    $this->validateUsername($value);
                    break;
                case 'password1':
                case 'password2':
                    $this->validatePassword($value);
                    break;
                case 'email';
                    $this->validateEmail($value);
                    break;
            }
        } catch (appException $e) {
            return ['error' => $e->getMessage()];
        }

        return ['result' => 'OK'];
    }

    /**
     * валидирует строку "логин";
     * логин может содержать только латинские буквы и цифры;
     * такого логина не должно быть в БД;
     * @param string $username
     * @throws appException
     * @return bool
     */
    public function validateUsername(string $username)
    {
        $match = preg_match('/^[a-zA-Z0-9-_]{3,25}$/', $username);
        if (!$match) {
            throw new appException('Логин может содержать латиницу, цифры, "_", "-", быть длиной от 3 до 25 символов.');
        }

        $user = User::findFirstByUsername($username);
        if ($user) {
            throw new appException('Такой логин уже зарегистрирован');
        }

        return true;
    }

    /**
     * валидирует строку "пароль";
     * минимальная длина пароля 6 символов;
     * пароль должен содержать буквы, цифры
     * @param string $password
     * @throws appException
     * @return bool
     */
    public function validatePassword(string $password)
    {
        if (!$password) {
            throw new appException("Пароль не может быть пустым");
        }

        $match1 = preg_match('/^[a-zA-Z0-9]{6,}$/u', $password);
        $match2 = preg_match('/^[0-9]{6,}$/u', $password);
        $match3 = preg_match('/^[a-zA-Z]{6,}$/u', $password);

        if ( (!$match1) or ($match2) or ($match3) ) {
            throw new appException('Пароль должен быть не короче 6 символов и состоять из букв латинского алфавита (A-z) и арабских цифр (0-9)');
        }

        return true;
    }

    /**
     * валидирует строку "почта";
     * такой почты не должно быть в БД
     * @param string $email
     * @throws appException
     * @return bool
     */
    public function validateEmail(string $email)
    {
        $valid1 = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$valid1) {
            throw new appException('Почта должна быть вида user@example.com');
        }

        $valid2 = new \hbattat\VerifyEmail($email, $this->myEmail);
        if (!$valid2->verify()) {
            throw new appException('Почта не прошла проверку');
        }

        return true;
    }
}