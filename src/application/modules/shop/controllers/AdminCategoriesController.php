<?php

class Shop_AdminCategoriesController extends ItRocks_Controller_Action {

    protected $_action  = '';
    protected $_page = 1;

    private $_category = null;
    private $_table = null;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->_action = $this->_request->getActionName();
        $this->_page = $this->_request->getParam('page', 1);

        if (in_array($this->_action, array('edit', 'delete', 'category-products'))) {
            $this->_table = new Shop_Model_DbTable_Categories();

            $this->_category = $this->_table->find($this->_getParam('id'))->current();
            if (!$this->_category){
                echo 'error';
            }
        }

        $this->view->assign('page', $this->_page);
    }

    public function indexAction()
    {
        $categoryTable = new Shop_Model_Categories();
        $categories = $categoryTable->getSortedAllCategories();
        $this->view->assign('categories', $categories);
        $this->_addMeta('Категории товаров', '', '');
    }

    public function addAction()
    {
        $categoryTable = new Shop_Model_DbTable_Categories();
        $categoryModel = new Shop_Model_Categories();
        $category = $categoryTable->createRow();
        $allCategories = $categoryModel->getSortedAllCategories();
        $allCategories = ['0' => 'Корневая категория'] + $allCategories;

        $form = new Shop_Form_AdminCategories($category, $allCategories);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    if (empty($formData['alias']))
                    {
                        $translit = new Model_Transliterate();
                        $formData['alias'] = $translit->transliterate($formData['title']);
                    }

                    $category->setFromArray($formData);
                    $category->save();

                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage(sprintf($this->view->translate('added'), $formData['title']));

                    $this->_helper->redirector('index', 'admin-categories', 'shop');
                } catch (Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                    $this->_helper->redirector('add', 'admin-categories', 'shop');
                }
            }
        }

        $this->_helper->viewRenderer('update');
        $this->view->assign('action', $this->_action);
        $this->view->assign('form', $form);
        $this->_addMeta('Добавить категорию', '');
    }

    public function editAction()
    {
        $categoryModel = new Shop_Model_Categories();
        $allCategories = $categoryModel->getSortedAllCategories();
        $categoryId = $this->_getParam('id');

        if (!empty($categoryId)){
            unset($allCategories[$categoryId]);
        }

        $parentId = $categoryModel->getParentIdById($categoryId);
        $allCategories = ['0' => 'Корневая категория'] + $allCategories;

        $form = new Shop_Form_AdminCategories($this->_category, $allCategories, $parentId, $categoryId);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    $this->_category->setFromArray($formData);
                    $this->_category->save();

                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage(sprintf($this->view->translate('added'), $formData['title']));

                    $this->_helper->redirector('index', 'admin-categories', 'shop');
                } catch (Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                }
            }
        }

        $this->_helper->viewRenderer('update');
        $this->view->assign('action', $this->_action);
        $this->view->assign('form', $form);
        $this->_addMeta('Редактировать категорию', '');
    }

    public function deleteAction()
    {
        $categoryModel = new Shop_Model_Categories();
        $allCategories = $categoryModel->getSortedAllCategories();
        $categoryId = $this->_getParam('id');

        if (!empty($categoryId)){
            unset($allCategories[$categoryId]);
        }

        $parentId = $categoryModel->getParentIdById($categoryId);
        $allCategories = ['0' => 'Корневая категория'] + $allCategories;

        $form = new Shop_Form_AdminCategoryRemove($this->_category, $allCategories, $parentId, $categoryId);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    if ($formData['feature'] == 'move')
                    {
                        // тут переносим товары с другую категорию
                    } elseif ($formData['feature'] == 'remove')
                    {
                        // удаляем товары категории
                    }
                    // удаляем категорию с базы данных
                    $this->_category->delete();
                    $this->_helper->redirector('index', 'admin-categories', 'shop');
                } catch (Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                }
            }
        }

        $this->_helper->viewRenderer('update');
        $this->view->assign('action', $this->_action);
        $this->view->assign('form', $form);
        $this->_addMeta('Удалить категорию', '');
    }

    public function categoryProductsAction() {
        $filtersTable = new Shop_Model_DbTable_Products();
        $paginator = $filtersTable->getPaginatorRows($this->_category->id, $this->_page);
        $this->view->assign('paginator', $paginator);
        $this->view->assign('category', $this->_category);
        $this->_addMeta('Товары категории ' . $this->_category->title, '');
    }
}