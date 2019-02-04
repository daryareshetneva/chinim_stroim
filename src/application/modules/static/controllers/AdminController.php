<?php

class Static_AdminController extends Zend_Controller_Action{
    
    protected $_page;
    protected $_sliderImage = null;
    
    public function init() 
    {
        $this->_helper->layout()->setLayout('admin');

        $action = $this->_request->getActionName();

        if (in_array($action, ['slider-image-edit', 'slider-image-delete'])) {
            $sliderTable = new Static_Model_DbTable_Slider();
            $this->_sliderImage = $sliderTable->find($this->_request->getParam('id'))->current();

            if (!$this->_sliderImage) {
                $this->_sliderNotFound();
            }
        }
    }
    
    public function indexAction() {
        $staticPagesTable = new Static_Model_DbTable_Static();

        $page = $this->_request->getParam( 'page', 1 );
        $pages = $staticPagesTable->getPagesTitles();

        $paginator = Zend_Paginator::factory( $pages );
        $paginator->setItemCountPerPage( 30 );
        $paginator->setCurrentPageNumber( $page );
        $this->view->assign( 'paginator', $paginator );
    }

    public function addAction()  {
        $diyTable = new Static_Model_DbTable_Static();
        $diy = $diyTable->createRow();
        $form = new Static_Form_Static($diy);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    $diy->setFromArray($formData);
                    $diy->content = $formData['staticContent'];
                    $diy->save();

                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage($this->view->translate('pageCreated'));
                    $this->_helper->redirector->gotoRoute(
                        array(
                            'action' => 'index',
                            'controller' => 'admin',
                            'module' => 'static',
                        )
                    );
                } catch (Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                    $this->_helper->redirector('add', 'admin', 'static');
                }
            }
        }
        $this->view->assign('form', $form);
    }

    public function editAction() 
    {
        $staticModel = new Static_Model_DbTable_Static();
        $pageName = $this->_getParam('alias');
        $page = $staticModel->find($pageName)->current();
        
        if (!$pageName || !$page)
            $this->_pageNotFound();
        else 
            $this->_page = $page;
        
        $form = new Static_Form_Static($this->_page);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                
                $this->_page->setFromArray($formData);
                $this->_page->content = $formData['staticContent'];
                $this->_page->save();
                
                $this->_helper->flashMessenger->setNamespace('messages')
                    ->addMessage($this->view->translate('pageEdited'));
                $this->_helper->redirector->gotoRoute(
                      array(
                          'action' => 'index',
                          'controller' => 'admin',
                          'module' => 'static', 
                          )
                );
            }
        }
        
        $this->view->form = $form;
        $this->view->title = $this->_page->title;
    }

    public function deleteAction() { // Удаление
        $staticModel = new Static_Model_DbTable_Static();
        $pageName = $this->_getParam('alias');
        if (( $pageName != 'home') && ($pageName != 'about') && ($pageName != 'contacts' )){
            $staticModel->deletePage($pageName);
        }
        $this->_helper->redirector('index', 'admin', 'static');
    }


    public function sliderAction() {
        $sliderTable = new Static_Model_DbTable_Slider();
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($sliderTable->getSliderImagesPagination()));
        $paginator->setCurrentPageNumber($this->_request->getParam('page', 1));
        $paginator->setPageRange(8);
        $paginator->setItemCountPerPage(30);

        $this->view->paginator = $paginator;
        $this->view->assign('maxPosition', $sliderTable->getMaxPosition());
    }

    public function sliderImageAddAction() {
        $this->_helper->viewRenderer('slider-form');

        $sliderTable = new Static_Model_DbTable_Slider();
        $slider = $sliderTable->createRow();

        $form = new Static_Form_Slider($slider);

        try {
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    $maxPosition = $sliderTable->getMaxPosition();
                    $imageHelper = new Model_Images_Slider();
                    $slider->image = $imageHelper->filter($_FILES['sliderImage']['tmp_name'], $_FILES['sliderImage']['name']);
                    $slider->title = $formData['title'];
                    $slider->url = $formData['url'];
                    $slider->position = $maxPosition + 1;
                    $slider->save();
                    $this->_helper->redirector('slider', 'admin', 'static');
                }
            }

        } catch (Exception $e) {
            $this->view->assign('error', $e->getMessage());
        }
        $this->view->assign('form', $form);
    }

    public function sliderImageEditAction() {
        $this->_helper->viewRenderer('slider-form');

        $form = new Static_Form_Slider($this->_sliderImage);

        try {
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    if (!empty($_FILES['sliderImage']['tmp_name'])) {
                        $imageHelper = new Model_Images_Slider();
                        $this->_sliderImage->image = $imageHelper->filter($_FILES['sliderImage']['tmp_name'], $_FILES['sliderImage']['name']);
                    }
                    $this->_sliderImage->title = $formData['title'];
                    $this->_sliderImage->message = $formData['message'];
                    $this->_sliderImage->url = $formData['url'];
                    $this->_sliderImage->save();

                    $this->_helper->redirector('slider', 'admin', 'static');
                }
            }

        } catch (Exception $e) {
            $this->view->assign('error', $e->getMessage());
        }
        $this->view->assign('form', $form);
    }

    public function sliderImageDeleteAction() {
        $this->_sliderImage->delete();
        $this->_helper->flashMessenger->setNamespace('messages')
            ->addMessage($this->view->translate('sliderDeleted'));
        $this->_helper->redirector('slider', 'admin', 'static');
    }

    public function sliderImageChangePositionAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            try {
                $sliderImagesModel = new Static_Model_SliderImages();
                $sliderImagesModel->changePositionById($formData['id'], $formData['position']);
                echo json_encode(['success' => 'success']);
            } catch (Exception $e) {
                echo json_encode(['error' => $this->view->translate($e->getMessage())]);
            }
        } else {
            echo json_encode(['error' => $this->view->translate('sliderErrorRequest')]);
        }
        exit;
    }

    public function feedbackAction(){
        $feedbackTable = new Static_Model_DbTable_Feedback();

        $page = $this->_request->getParam( 'page', 1 );
        $messages = $feedbackTable->getAll();

        $paginator = Zend_Paginator::factory( $messages );
        $paginator->setItemCountPerPage( 30 );
        $paginator->setCurrentPageNumber( $page );
        $this->view->assign( 'paginator', $paginator );
    }

    public function feedbackShowAction(){
        $feedbackTable = new Static_Model_DbTable_Feedback();
        $messageId = $this->_request->getParam('id');
        $message = $feedbackTable->getMessageById($messageId);

        $this->view->assign('message', $message);
    }

    public function feedbackDeleteAction(){
        $feedbackTable  = new Static_Model_DbTable_Feedback();
        $messageID      = $this->_getParam('id');
        $feedbackTable->deleteMessage($messageID);
        $this->_helper->redirector('feedback', 'admin', 'static');
    }
    
    protected function _pageNotFound() 
    {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
                ->addMessage($this->view->translate('pageNotFound'));
        
        $this->_redirect('/admin');
    }
}