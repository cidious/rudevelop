<?php
namespace Controllers;

use Library\Signup;
use Library\Validation;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
    }

    public function registerAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $email = $this->request->getPost('email');

            $message = '';
            $signup = new Signup();
            try {
                $signup->signUp($username, $email, $password);
            } catch (\Exception $e) {
                $message = $e->getMessage();
            }

            $remember = $this->request->getPost('remember');
            if ($remember) {
                $location = 'profile';
                $this->session->set('auth', [
                    'username' => $username,
                    'email' => $email,
                ]);
            } else {
                $location = '';
                $this->session->destroy(true);
                session_unset();
            }

            return $this->outJson([
                'result' => $location,
                'message' => $message,
            ]);
        }

        return true;
    }

    public function validateAction()
    {
        if (!$this->request->isAjax()) {
            return false;
        }
        if (!$this->request->isPost()) {
            return $this->outJson(['error' => 'no post']);
        }

        $field = $this->request->getPost('field');
        $value = $this->request->getPost('value');
        $validation = new Validation();
        $result = $validation->validateAny($field, $value);

        return $this->outJson($result);
    }

    public function profileAction()
    {

    }

    public function trespassingAction()
    {

    }

}

