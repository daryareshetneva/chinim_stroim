<?php

class HotelRooms_IndexController extends ItRocks_Controller_Action{

    public function init() {
        $tagsModel = new Model_Tags();
        $tags = $tagsModel->getTagsByMCA(
            $this->_request->getModuleName(),
            $this->_request->getControllerName(),
            $this->_request->getActionName()
        );

        $this->_addMeta($tags['metaTitle'], $tags['metaDescription']);
        $this->view->assign('title', $tags['title']);
    }
       
    public function indexAction(){
        $alias = $this->_request->getParam('alias');
        $categoriesTable = new HotelRooms_Model_DbTable_Categories();

        $categories = $categoriesTable->getAll();
        if (!$alias) {
            $categoryId = $categories[0]['id'];
        } else {
            $category = $categoriesTable->getByAlias($alias);
            if (!$category) {
                $categoryId = $categories[0]['id'];
            } else {
                $categoryId = $category->id;
            }
        }
        $roomsTable = new HotelRooms_Model_DbTable_Rooms();
        $rooms = $roomsTable->getByCategoryId($categoryId);

        $this->view->assign('rooms', $rooms);
        $this->view->assign('categories', $categories);
    }

    public function sliderAction() {
        $categoryId = $this->_request->getParam('categoryId');
        $limit = $this->_request->getParam('limit', '');
        $template = $this->_request->getParam('template', '');

        if (!empty($template)) {
            $this->_helper->viewRenderer($template);
        }

        $roomsTable = new HotelRooms_Model_DbTable_Rooms();

        $rooms = $roomsTable->getByCategoryId($categoryId, $limit);

        $this->view->assign('rooms', $rooms);
    }

    public function showAction() {
        $alias = $this->_request->getParam('alias');
        $roomsTable = new HotelRooms_Model_DbTable_Rooms();
        $roomPhotosTable = new HotelRooms_Model_DbTable_RoomPhotos();
        $roomPropertiesTable = new HotelRooms_Model_DbTable_Properties();
        $roomCategoriesTable = new HotelRooms_Model_DbTable_Categories();

        $room = $roomsTable->getByAlias($alias);
        if (!$room) {
            $this->_helper->redirector('index', 'index', 'hotelrooms');
        }

        $category = $roomCategoriesTable->find($room->categoryId)->current();

        $this->view->assign('category', $category);
        $this->view->assign('photos', $roomPhotosTable->getByItemId($room->id));
        $this->view->assign('properties', $roomPropertiesTable->getByRoomId($room->id));
        $this->view->assign('room', $room);

        $this->view->headTitle()->prepend($room->title . ' - ' . $category->title);
    }

    public function modalWindowAction() {
        $form = new HotelRooms_Form_Request();

        $this->view->assign('form', $form);
    }

    public function ajaxRequestAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $form = new HotelRooms_Form_Request();
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $feedbackTable = new Static_Model_DbTable_Feedback();
                $feedback = $feedbackTable->createRow([
                    'phone' => $formData['phone'],
                    'name' => $formData['fio'],
                    'date' => date('Y-m-d H:i:s')
                ]);
                if (isset($formData['room'])) {
                    $roomsTable = new HotelRooms_Model_DbTable_Rooms();
                    $room = $roomsTable->find($formData['room'])->current();
                    if ($room) {
                        $feedback->message = 'Номер: ' . $room->title;
                    }
                } else {
                    $room = null;
                }

                $feedback->save();

                $roomName = ($room) ? $room->title : '';
                $modelMail = new Model_Mail();
                $modelMail->sendNewRequest($formData['phone'], $formData['fio'], date('Y-m-d H:i:s'), $roomName);

                echo json_encode(['success' => 'success']);
                exit;
            }
        }
        echo json_encode(['error' => 'error']);
        exit;
    }

    public function successRequestAction() {

    }

    public function categoriesAjaxAction() {
        $this->_helper->layout()->disableLayout();
        $alias = $this->_request->getParam('alias');
        $categoriesTable = new HotelRooms_Model_DbTable_Categories();

        $categories = $categoriesTable->getAll();
        if (!$alias) {
            $categoryId = $categories[0]['id'];
        } else {
            $category = $categoriesTable->getByAlias($alias);
            if (!$category) {
                $categoryId = $categories[0]['id'];
            } else {
                $categoryId = $category->id;
            }
        }

        $roomsTable = new HotelRooms_Model_DbTable_Rooms();
        $rooms = $roomsTable->getByCategoryId($categoryId);

        $this->view->assign('rooms', $rooms);
    }
}
