<?php

class HotelRooms_AdminRoomController extends Zend_Controller_Action{

    protected $_category;
    protected $_room;
    protected $_roomPhoto;
    protected $_roomProperty;
    protected $_page = 1;
    
    public function init(){
        $this->_helper->layout->setLayout('admin');
        $action = $this->_request->getActionName();
        $this->_page = $this->_request->getParam('page', 1);

        $categoriesTable = new HotelRooms_Model_DbTable_Categories();
        $this->_category = $categoriesTable->find($this->_getParam('categoryId'))->current();
        if (!$this->_category) $this->_categoryNotFound();
 
        if (in_array($action, array('edit', 'delete'))) {
            $roomsTable = new HotelRooms_Model_DbTable_Rooms();
            $this->_room = $roomsTable->find($this->_getParam('id'))->current();
            if (!$this->_room) $this->_roomNotFound();

            $this->view->assign('room', $this->_room);
        }

        if (in_array($action, array('photos', 'add-photo', 'edit-photo', 'delete-photo', 'properties', 'add-property', 'edit-property', 'delete-property'))) {
            $roomsTable = new HotelRooms_Model_DbTable_Rooms();
            $this->_room = $roomsTable->find($this->_getParam('roomId'))->current();
            if (!$this->_room) $this->_roomNotFound();

            $this->view->assign('room', $this->_room);
        }

        if (in_array($action, array('edit-photo', 'delete-photo'))) {
            $roomPhotosTable = new HotelRooms_Model_DbTable_RoomPhotos();
            $this->_roomPhoto = $roomPhotosTable->find($this->_getParam('id'))->current();
            if (!$this->_roomPhoto) $this->_roomPhotoNotFound();
        }
        if (in_array($action, array('edit-property', 'delete-property'))) {
            $roomPropertiesTable = new HotelRooms_Model_DbTable_Properties();
            $this->_roomProperty = $roomPropertiesTable->find($this->_getParam('id'))->current();
            if (!$this->_roomProperty) $this->_roomPropertyNotFound();
        }

        $this->view->assign('action', $action);
        $this->view->assign('page', $this->_page);
        $this->view->assign('category', $this->_category);
    }
        
    public function indexAction() {
        $roomsTable = new HotelRooms_Model_DbTable_Rooms();

	    $rooms = $roomsTable->getByCategoryId($this->_category->id);

	    $paginator = Zend_Paginator::factory($rooms);
	    $paginator->setItemCountPerPage(20);
	    $paginator->setCurrentPageNumber($this->_page);

	    $this->view->assign('paginator', $paginator);
    }
    
    public function addAction() {
        $this->_helper->viewRenderer('show-form');

        $roomsTable = new HotelRooms_Model_DbTable_Rooms();
        $room = $roomsTable->createRow();
        
        $form = new HotelRooms_Form_Room($room);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $transliterateModel = new Model_Transliterate();
                $formData['alias'] = $transliterateModel->transliterate($formData['title']);

                $formData['categoryId'] = $this->_category->id;

                $formData['bed'] = (isset($formData['bed'])) ? 1 : 0;
                $formData['fridge'] = (isset($formData['fridge'])) ? 1 : 0;
                $formData['jacuzzi'] = (isset($formData['jacuzzi'])) ? 1 : 0;
                $formData['satTv'] = (isset($formData['satTv'])) ? 1 : 0;
                $formData['electrofireplace'] = (isset($formData['electrofireplace'])) ? 1 : 0;
                $formData['sauna'] = (isset($formData['sauna'])) ? 1 : 0;
                $formData['wifi'] = (isset($formData['wifi'])) ? 1 : 0;
                $formData['shower'] = (isset($formData['shower'])) ? 1 : 0;
                $formData['fireplace'] = (isset($formData['fireplace'])) ? 1 : 0;
                $formData['conditioner'] = (isset($formData['conditioner'])) ? 1 : 0;
                $formData['cupboard'] = (isset($formData['cupboard'])) ? 1 : 0;
                $formData['minibar'] = (isset($formData['minibar'])) ? 1 : 0;


                if (!empty($_FILES['photo']['tmp_name'])) {
                    $imageHelper = new HotelRooms_Model_Images();
                    $formData['photo'] = $imageHelper->filter($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
                }

                $room->setFromArray($formData);
                $room->save();

                $this->_helper->redirector('index', 'admin-room', 'hotelrooms', ['categoryId' => $this->_category->id]);
            }
        }
        
        $this->view->assign('form', $form);
    }
    
    public function editAction() {
        $this->_helper->viewRenderer('show-form');

        $form = new HotelRooms_Form_Room($this->_room);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $transliterateModel = new Model_Transliterate();
                $formData['alias'] = $transliterateModel->transliterate($formData['title']);

                $formData['categoryId'] = $this->_category->id;

                $formData['bed'] = (isset($formData['bed'])) ? 1 : 0;
                $formData['fridge'] = (isset($formData['fridge'])) ? 1 : 0;
                $formData['jacuzzi'] = (isset($formData['jacuzzi'])) ? 1 : 0;
                $formData['satTv'] = (isset($formData['satTv'])) ? 1 : 0;
                $formData['electrofireplace'] = (isset($formData['electrofireplace'])) ? 1 : 0;
                $formData['sauna'] = (isset($formData['sauna'])) ? 1 : 0;
                $formData['wifi'] = (isset($formData['wifi'])) ? 1 : 0;
                $formData['shower'] = (isset($formData['shower'])) ? 1 : 0;
                $formData['fireplace'] = (isset($formData['fireplace'])) ? 1 : 0;
                $formData['conditioner'] = (isset($formData['conditioner'])) ? 1 : 0;
                $formData['cupboard'] = (isset($formData['cupboard'])) ? 1 : 0;
                $formData['minibar'] = (isset($formData['minibar'])) ? 1 : 0;


                if (!empty($_FILES['photo']['tmp_name'])) {
                    $imageHelper = new HotelRooms_Model_Images();
                    $formData['photo'] = $imageHelper->filter($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
                }

                $this->_room->setFromArray($formData);
                $this->_room->save();

                $this->_helper->redirector('index', 'admin-room', 'hotelrooms', ['categoryId' => $this->_category->id]);
            }
        }
        
        $this->view->assign('form', $form);
    }
        
    public function deleteAction() {
        $roomPhotosTable = new HotelRooms_Model_DbTable_RoomPhotos();
        $roomPropertiesTable = new HotelRooms_Model_DbTable_Properties();
        $roomPhotosTable->deleteByRoomId($this->_room->id);
        $roomPropertiesTable->deleteByRoomId($this->_room->id);
        $this->_room->delete();
        
        $this->_helper->flashMessenger->setNamespace('messages')
                                      ->addMessage('Номер удален');

        $this->_helper->redirector('index', 'admin-room', 'hotelrooms', ['categoryId' => $this->_category->id]);
    }

    public function photosAction() {
        $roomPhotosTable = new HotelRooms_Model_DbTable_RoomPhotos();

        $photos = $roomPhotosTable->getByItemId($this->_room->id);

        $paginator = Zend_Paginator::factory($photos);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($this->_page);

        $this->view->assign('paginator', $paginator);
    }

    public function addPhotoAction() {
        $this->_helper->viewRenderer('show-photo-form');

        $roomPhotosTable = new HotelRooms_Model_DbTable_RoomPhotos();
        $photo = $roomPhotosTable->createRow();

        $form = new HotelRooms_Form_RoomPhoto($photo);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $formData['roomId'] = $this->_room->id;

                if (!empty($_FILES['photo']['tmp_name'])) {
                    $imageHelper = new HotelRooms_Model_Images();
                    $formData['photo'] = $imageHelper->filter($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
                }

                $photo->setFromArray($formData);
                $photo->save();

                $this->_helper->redirector('photos', 'admin-room', 'hotelrooms', ['categoryId' => $this->_category->id, 'roomId' => $this->_room->id]);
            }
        }

        $this->view->assign('form', $form);
    }

    public function editPhotoAction() {
        $this->_helper->viewRenderer('show-photo-form');

        $form = new HotelRooms_Form_RoomPhoto($this->_roomPhoto);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                if (!empty($_FILES['photo']['tmp_name'])) {
                    $imageHelper = new HotelRooms_Model_Images();
                    $formData['photo'] = $imageHelper->filter($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
                }

                $this->_roomPhoto->setFromArray($formData);
                $this->_roomPhoto->save();

                $this->_helper->redirector('photos', 'admin-room', 'hotelrooms', ['categoryId' => $this->_category->id, 'roomId' => $this->_room->id]);
            }
        }

        $this->view->assign('form', $form);
    }

    public function deletePhotoAction() {
        $this->_roomPhoto->delete();
        $this->_helper->flashMessenger->setNamespace('messages')
            ->addMessage('Фотография удалена');

        $this->_helper->redirector('photos', 'admin-room', 'hotelrooms', ['categoryId' => $this->_category->id, 'roomId' => $this->_room->id]);
    }

    public function propertiesAction() {
        $propertiesTable = new HotelRooms_Model_DbTable_Properties();

        $properties = $propertiesTable->getByRoomId($this->_room->id);

        $paginator = Zend_Paginator::factory($properties);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($this->_page);

        $this->view->assign('paginator', $paginator);
    }

    public function addPropertyAction() {
        $this->_helper->viewRenderer('show-property-form');

        $propertiesTable = new HotelRooms_Model_DbTable_Properties();
        $property = $propertiesTable->createRow();

        $form = new HotelRooms_Form_Property($property);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $formData['roomId'] = $this->_room->id;

                $property->setFromArray($formData);
                $property->save();

                $this->_helper->redirector('properties', 'admin-room', 'hotelrooms', ['categoryId' => $this->_category->id, 'roomId' => $this->_room->id]);
            }
        }

        $this->view->assign('form', $form);
    }

    public function editPropertyAction() {
        $this->_helper->viewRenderer('show-property-form');

        $form = new HotelRooms_Form_Property($this->_roomProperty);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {

                $this->_roomProperty->setFromArray($formData);
                $this->_roomProperty->save();

                $this->_helper->redirector('properties', 'admin-room', 'hotelrooms', ['categoryId' => $this->_category->id, 'roomId' => $this->_room->id]);
            }
        }

        $this->view->assign('form', $form);
    }

    public function deletePropertyAction() {
        $this->_roomProperty->delete();

        $this->_helper->flashMessenger->setNamespace('messages')
            ->addMessage('Свойство удалено');

        $this->_helper->redirector('properties', 'admin-room', 'hotelrooms', ['categoryId' => $this->_category->id, 'roomId' => $this->_room->id]);
    }

    protected function _roomNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage('Запись не найдена');

        $this->_helper->redirector('index', 'admin-room', 'hotelrooms', ['categoryId' => $this->_category->id]);
    }

    protected function _roomPhotoNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage('Запись не найдена');

        $this->_helper->redirector('photos', 'admin-room', 'hotelrooms', ['categoryId' => $this->_category->id, 'roomId' => $this->_room->id]);
    }

    protected function _roomPropertyNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage('Запись не найдена');

        $this->_helper->redirector('properties', 'admin-room', 'hotelrooms', ['categoryId' => $this->_category->id, 'roomId' => $this->_room->id]);
    }
    
    protected function _categoryNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
							          ->addMessage('Запись не найдена');
        
        $this->_helper->redirector('index', 'admin', 'hotelrooms');
    }
}
