<?php

class Specialists_IndexController extends ItRocks_Controller_Action{
       
    public function indexAction(){
        $specialistsTable = new Specialists_Model_DbTable_Specialists();

        $items = $specialistsTable->getAll();

        $this->view->assign('items', $items);
    }

    public function showAction() {
        $id = $this->_request->getParam('id');
        $specialistsTable = new Specialists_Model_DbTable_Specialists();

        $specialist = $specialistsTable->find($id)->current();
        if (!$specialist) {
            $this->_helper->redirector('index', 'index', 'specialists');
        }

        $this->_addMeta($specialist->metaTitle, $specialist->metaDescription, $specialist->metaKeywords);

        $this->view->assign('specialist', $specialist);
    }

    public function sliderAction() {
        $specialistsTable = new Specialists_Model_DbTable_Specialists();

        $items = $specialistsTable->getAll('id');

        $this->view->assign('items', $items);
    }
}
