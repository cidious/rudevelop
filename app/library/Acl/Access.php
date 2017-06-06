<?php
namespace Library\Acl;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

class Access extends Plugin
{
    const AUTH_GUEST = 'Guest';
    const AUTH_USER = 'User';
    const AUTH_ADMIN = 'Admin';

    /**
     * @var string: Guests, notActivated, Users, Admin
     */
    protected $role = 'Guests';
    protected static $auth = false;
    protected static $allowed = Acl::DENY;

    protected static $controller;
    protected static $action;
    protected static $params;

    protected static $redirect_url = [];
    protected static $forward_url = [];

    protected $authSession = [];

    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        self::$controller = $dispatcher->getControllerName();
        self::$action = $dispatcher->getActionName();
        self::$params = $dispatcher->getParams();

        $acl = $this->getAcl();
        $this->user();
        $this->role = $this->setRole();
        self::$allowed = $acl->isAllowed($this->role, self::$controller, self::$action);

        $this->action();
        $this->di->set('access', $this);

        return $this->forward($dispatcher);
    }

    protected static function guestUrls()
    {
        return [
            'index' => ['index', 'register', 'validate'],
        ];
    }

    protected static function userUrls()
    {
        return [
            'index' => ['profile', 'trespassing'],
        ];
    }

    protected static function adminUrls()
    {
        return [
        ];
    }

    /**
     * Returns an existing or new access control list
     *
     * @returns AclList
     */
    public function getAcl()
    {
        $acl = new AclList();
        $acl->setDefaultAction(Acl::DENY);

        //Register roles
        $roles = [
            'guest' => new Role(self::AUTH_GUEST),
            'user' => new Role(self::AUTH_USER),
            'admin' => new Role(self::AUTH_ADMIN)
        ];
        foreach ($roles as $role) {
            $acl->addRole($role);
        }

        //Public area resources
        $publicResources = self::guestUrls();
        foreach ($publicResources as $resource => $actions) {
            $acl->addResource(new Resource($resource), $actions);
        }
        //Private area resources
        $privateResources = self::userUrls();
        foreach ($privateResources as $resource => $actions) {
            $acl->addResource(new Resource($resource), $actions);
        }
        //Admin area resources
        $adminResources = self::adminUrls();
        foreach ($adminResources as $resource => $actions) {
            $acl->addResource(new Resource($resource), $actions);
        }

        foreach ($roles as $role) {
            foreach ($publicResources as $resource => $actions) {
                foreach ($actions as $action) {
                    $acl->allow($role->getName(), $resource, $action);
                }
            }
        }
        foreach ($privateResources as $resource => $actions) {
            foreach ($actions as $action) {
                $acl->allow(self::AUTH_USER, $resource, $action);
                $acl->allow(self::AUTH_ADMIN, $resource, $action);
            }
        }
        foreach ($adminResources as $resource => $actions) {
            foreach ($actions as $action) {
                $acl->allow(self::AUTH_ADMIN, $resource, $action);
            }
        }

        $this->persistent->acl = $acl;

        return $this->persistent->acl;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getAuth()
    {
        return self::$auth;
    }

    public function show()
    {
        return self::$auth;
    }

    public function getAllowed()
    {
        return self::$allowed;
    }

    public function getController()
    {
        return self::$controller;
    }

    public function getAction()
    {
        return self::$action;
    }

    /**
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function forward(Dispatcher $dispatcher)
    {
        if (0 !== count(self::$forward_url)) {
            $dispatcher->forward(self::$forward_url);

            return false;
        }

        if (0 !== count(self::$redirect_url)) {
            $url = self::$redirect_url["controller"] . "/" . self::$redirect_url["action"];

            $this->response->redirect($url);

            return false;
        }

        return true;
    }

    private function user()
    {
        if ($this->session->has('auth')) {
            self::$auth = true;
            $this->authSession = $this->session->get("auth");

        } else {
            self::$auth = false;
        }

        return true;
    }

    private function setRole()
    {
        if (!self::$auth) {
            $role = self::AUTH_GUEST;
        } elseif ($this->authSession["admin"] == 0) {
            $role = self::AUTH_USER;
        } else {
            $role = self::AUTH_ADMIN;
        }

        return $role;
    }

    /**
     * Страницы, которые не должны редиректиться
     *
     * @return array
     */
    private static function notRedirectedPage()
    {
        return [
            'index' => ['index', 'register'],
        ];
    }

    /**
     * Формирует URL для редиректа или форварда
     *
     * @return bool
     */
    public function action()
    {
        self::$forward_url = [];
        self::$redirect_url = [];

        // не хватает прав для доступа к странице
        if (self::$allowed != Acl::ALLOW) {
            if ($this->role === 'Guest') {
                self::$forward_url = [
                    'controller' => 'index',
                    'action'     => 'register'
                ];
            } else { // если юзер зарегистрированный, но ему сюда нельзя
                self::$forward_url = [
                    'controller' => 'index',
                    'action'     => 'trespassing'
                ];
            }
        } else {
            if (self::$auth) {
                if (!self::isRedirectedPage()) {
                    return true;
                }

                if (self::isAdminPage()) {
                    return true;
                }
            }
        }

        return true;
    }

    /**
     * Должна ли страница редиректиться?
     *
     * @return bool
     */
    private static function isRedirectedPage()
    {
        if (
            (isset(self::notRedirectedPage()[self::$controller]))
            and
            (in_array(self::$action, self::notRedirectedPage()[self::$controller], false))
        ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Страница админа?
     *
     * @return bool
     */
    private static function isAdminPage()
    {
        $t = self::adminUrls();
        if ( (isset(self::adminUrls()[self::$controller])) and (in_array(self::$action, self::adminUrls()[self::$controller], false)) ) {
            return true;
        } else {
            return false;
        }
    }

}
