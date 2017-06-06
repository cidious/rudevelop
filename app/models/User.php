<?php
namespace Models;

use Phalcon\Mvc\Model;

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

}