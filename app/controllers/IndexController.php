<?php
namespace Controllers;

use Library\appException;
use Models\Item, Models\Category;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
    }

    public function registerAction()
    {

    }

    public function validateAction()
    {
        if (!$this->request->isAjax()) {
            return false;
        }

        return $this->outJson([]);
    }

    public function profileAction()
    {

    }

    public function trespassingAction()
    {

    }

    /**
     * взять товар из БД, отдать массив завернутый в JSON
     * @param int $id
     * @return bool|\Phalcon\Http\Response
     */
    public function ajaxGetItemAction($id)
    {
        if (!$this->request->isAjax()) {
            return false;
        }
        $item = Item::findFirst($id);
        if (!$item) {
            return $this->outJson(['error' => 'item not found:'.$id]);
        }
        return $this->outJson(['result' => $item->toArray()]);
    }

    /**
     * взять товар из БД, отдать HTML завернутый в JSON
     * @param int $id
     * @return bool|\Phalcon\Http\Response
     */
    public function ajaxGetRowAction($id)
    {
        if (!$this->request->isAjax()) {
            return false;
        }
        $item = Item::findFirst($id);
        if (!$item) {
            return $this->outJson(['error' => 'item not found:'.$id]);
        }
        $this->view->i = $item;
        $this->view->disable();
        $content = $this->view->getRender('index', 'row', null,
            function ($view) {
                $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
            }
        );
        return $this->outJson([
            'result' => $content,
            'id' => $item->id
        ]);
    }

    /**
     * удалить товар из БД
     * @return bool|\Phalcon\Http\Response
     * @throws appException
     */
    public function ajaxDelItemAction()
    {
        if (!$this->request->isAjax()) {
            return false;
        }
        if (!$this->request->isPost()) {
            throw new appException('no post');
        }
        $id = $this->request->getPost('id');
        $item = Item::findFirst($id);
        if (!$item) {
            return $this->outJson(['error' => 'item not found:'.$id]);
        }
        $result = $item->delete();
        if ($result) {
            return $this->outJson(['result' => 'OK' ]);
        } else {
            return $this->outJson(['error' => 'cannot delete item:'.
                implode(',', $item->getMessages()) ]);
        }
    }

    /**
     * @return bool|\Phalcon\Http\Response
     * @throws appException
     */
    public function ajaxSaveItemAction()
    {
        if (!$this->request->isAjax()) {
            return false;
        }
        if (!$this->request->isPost()) {
            throw new appException('no post');
        }

        $id = $this->request->getPost('id');

        if ($id) {
            $item = Item::findFirst($id);
            if (!$item) {
                throw new appException('item not found:'.$id);
            }
        } else {
            $item = new Item();
        }
        $item->name = $this->request->getPost('name');
        $item->category_id = $this->request->getPost('category');
        $item->price = $this->request->getPost('price');
        $item->description = $this->request->getPost('description');
        $result = $item->save();
        if (!$result) {
            throw new appException('cannot save item:'.implode(',', $item->getMessages()));
        }

        return $this->outJson([
            'result' => 'OK',
            'id' => $item->id
        ]);
    }

    public function makeupAction()
    {
        $this->view->disable();
        return $this->view->getRender('index', 'makeup', null,
            function ($view) {
                $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
            }
        );
    }
}

