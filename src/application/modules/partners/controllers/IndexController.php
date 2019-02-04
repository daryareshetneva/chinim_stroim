<?php

class Partners_IndexController extends ItRocks_Controller_Action{
       
    public function indexAction(){
        $partnersTable = new Partners_Model_DbTable_Partners();

        $items = $partnersTable->getAll();

        $this->view->assign('items', $items);
    }

    public function sliderAction() {
        $partnersTable = new Partners_Model_DbTable_Partners();

        $items = $partnersTable->getAll();

        $this->view->assign('items', $items);
    }
}
