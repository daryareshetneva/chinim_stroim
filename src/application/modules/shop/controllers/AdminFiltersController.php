<?php

class Shop_AdminFiltersController extends Zend_Controller_Action {

    protected $_page = 1;
    protected $_filter = null;
    protected $_filterElement = null;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $action = $this->_request->getActionName();
        $this->_page = $this->_request->getParam('page');

        if (in_array($action, ['edit', 'delete', 'filter-elements-list'])) {
            $filtersTable = new Shop_Model_DbTable_Filters();
            $this->_filter = $filtersTable->find($this->_request->getParam('id'))->current();
            if (!$this->_filter) {
                $this->_filterNotFound();
            }
        }

        if (in_array($action, ['add-element', 'edit-element', 'remove-element'])) {
            $filtersTable = new Shop_Model_DbTable_Filters();
            $this->_filter = $filtersTable->find($this->_request->getParam('filterId'))->current();
            if (!$this->_filter) {
                $this->_filterNotFound();
            }
        }

        if (in_array($action, ['edit-element', 'remove-element'])) {
            $filterElementsTable = new Shop_Model_DbTable_FilterElements();
            $this->_filterElement = $filterElementsTable->find($this->_request->getParam('id'))->current();
            if (!$this->_filter) {
                $this->_filterElementNotFound();
            }
        }


        $this->view->assign('page', $this->_page);
        $this->view->assign('action', $action);
    }

    public function indexAction() {
        $filtersTable = new Shop_Model_DbTable_Filters();
        $paginator = $filtersTable->getPaginatorRows($this->_page);
        $this->view->assign('paginator', $paginator);
    }

    public function addAction() {
        $this->_helper->viewRenderer('show-filter-form');

        $filtersTable = new Shop_Model_DbTable_Filters();
        $filter = $filtersTable->createRow();

        $form = new Shop_Form_AdminFilter($filter);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $transliterate = new Model_Transliterate();
                $formData['alias'] = $transliterate->transliterate($formData['title']);
                $filter->setFromArray($formData);
                $filter->save();

                $this->_helper->flashMessenger->setNamespace('messages')
                    ->addMessage(sprintf($this->view->translate('added'), $formData['title']));

                $this->_helper->redirector('index', 'admin-filters', 'shop');
            }
        }

        $this->view->assign('form', $form);
    }

    public function editAction() {
        $this->_helper->viewRenderer('show-filter-form');

        $form = new Shop_Form_AdminFilter($this->_filter);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $transliterate = new Model_Transliterate();
                $formData['alias'] = $transliterate->transliterate($formData['title']);
                $this->_filter->setFromArray($formData);
                $this->_filter->save();

                $this->_helper->flashMessenger->setNamespace('messages')
                    ->addMessage(sprintf($this->view->translate('update'), $formData['title']));

                $this->_helper->redirector('index', 'admin-filters', 'shop');
            }
        }

        $this->view->assign('form', $form);
    }

    public function deleteAction() {
        $filterElementsTable = new Shop_Model_DbTable_FilterElements();
        $filterElementsTable->deleteByFilterId($this->_filter->id);
        $this->_filter->delete();
        $this->_helper->flashMessenger->setNamespace('messages')
            ->addMessage($this->view->translate('delete'));

        $this->_helper->redirector('index', 'admin-filters', 'shop');
    }

    public function filterElementsListAction() {
        $filterElementsTable = new Shop_Model_DbTable_FilterElements();
        $paginator = $filterElementsTable->getPaginatorRows($this->_filter->id, $this->_page);
        $this->view->assign('paginator', $paginator);
        $this->view->assign('filter', $this->_filter);
    }

    public function addElementAction() {
        $this->_helper->viewRenderer('show-element-form');

        $filterElementsTable = new Shop_Model_DbTable_FilterElements();
        $filterElement = $filterElementsTable->createRow();

        $form = new Shop_Form_AdminFilterElement($filterElement);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $formData['filterId'] = $this->_filter->id;
                $filterElement->setFromArray($formData);
                $filterElement->save();
                $this->_helper->flashMessenger->setNamespace('messages')
                    ->addMessage(sprintf($this->view->translate('added'), $formData['title']));

                $this->_helper->redirector('filter-elements-list', 'admin-filters', 'shop', ['id' => $this->_filter->id]);
            }
        }

        $this->view->assign('form', $form);
    }

    public function editElementAction() {
        $this->_helper->viewRenderer('show-element-form');

        $form = new Shop_Form_AdminFilterElement($this->_filterElement);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $this->_filterElement->setFromArray($formData);
                $this->_filterElement->save();
                $this->_helper->flashMessenger->setNamespace('messages')
                    ->addMessage(sprintf($this->view->translate('update'), $formData['title']));

                $this->_helper->redirector('filter-elements-list', 'admin-filters', 'shop', ['id' => $this->_filter->id]);
            }
        }

        $this->view->assign('form', $form);
    }

    public function removeElementAction() {
        $this->_filterElement->delete();
        $this->_helper->flashMessenger->setNamespace('messages')
            ->addMessage($this->view->translate('delete'));

        $this->_helper->redirector('filter-elements-list', 'admin-filters', 'shop', ['id' => $this->_filter->id]);
    }

    public function ajaxGetFilterElementsAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $filterId = $this->_request->getParam('filterId', '');
        $filtersTable = new Shop_Model_DbTable_Filters();
        $filter = $filtersTable->find($filterId)->current();
        if ($filter) {
            $filterElementsTable = new Shop_Model_DbTable_FilterElements();
            $filters = $filterElementsTable->getElementsPairsByFilterId($filter->id);
            echo json_encode($filters);
        }
        exit;
    }

    private function _filterNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage($this->view->translate('shopFilterNotFound'));
    }

    private function _filterElementNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage($this->view->translate('shopFilterElementNotFound'));
    }

}