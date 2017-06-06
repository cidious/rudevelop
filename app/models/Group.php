<?php
namespace Models;

use Phalcon\Mvc\Model;

class Group extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $name;
    /** @var int */
    public $user;
    /** @var int */
    public $admin;

}