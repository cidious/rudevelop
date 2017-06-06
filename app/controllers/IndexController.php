<?php
namespace Controllers;

use Library\Validation;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
    }

    public function registerAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
        }
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

