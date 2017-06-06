<?php
namespace Models;

use Phalcon\Mvc\Model, Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness, Phalcon\Validation\Validator\Email;

class User extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $username;
    /** @var string */
    public $password;
    /** @var string */
    public $salt;
    /** @var string */
    public $email;
    /** @var int */
    public $group_id;

    public function initialize() {
        $this->belongsTo('group_id', 'Models\\Group', 'id',
            [ 'alias' => 'group' ]);
    }

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new Email([
                'model' => $this,
                'message' => 'Email указан не верно'
            ])
        );

        $validator->add(
            'email',
            new Uniqueness([
                'model' => $this,
                'message' => 'Такой e-mail уже используется',
            ])
        );

        $validator->add(
            'username',
            new Uniqueness([
                'model' => $this,
                'message' => 'Логин занят',
            ])
        );

        return $this->validate($validator);
    }
}