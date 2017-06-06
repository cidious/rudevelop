<?php
namespace Library;

use Phalcon\Mvc\User\Component;

class Signup extends Component
{
    public $default_group_id = 1;

    /**
     * регистрирует нового пользователя
     * @param string $username
     * @param string $email
     * @param string $password
     * @return \Models\User
     * @throws \Exception
     */
    public function signUp(string $username, string $email, string $password)
    {
        $validation = new Validation();
        try {
            $validation->validateUsername($username);
            $validation->validatePassword($username, $password);
            $validation->validateEmail($email);
        } catch (\Exception $e) {
            throw $e;
        }

        $User = new \Models\User();

        list($cypher, $salt) = Password::cypherPasswd($password);

        $User->username = $username;
        $User->password = $cypher;
        $User->salt     = $salt;
        $User->email    = $email;
        $User->group_id = $this->default_group_id;

        if (!$User->save()) {
            throw new appException(implode(', ', $User->getMessages()));
        }

        return $User;
    }

}
