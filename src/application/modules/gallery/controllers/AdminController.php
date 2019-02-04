<?php

class Gallery_AdminController extends Zend_Controller_Action {

    protected $_categoryModel;
    protected $_photoModel;
    protected $_relationsModel;
    protected $_params;

    public function __construct( \Zend_Controller_Request_Abstract $request, \Zend_Controller_Response_Abstract $response, array $invokeArgs = array( ) ) {
        parent::__construct( $request, $response, $invokeArgs );

        $this->_categoryModel = new Gallery_Model_Category();
        $this->_photoModel = new Gallery_Model_Photo();
        $this->_relationsModel = new Gallery_Model_Relations();

        $this->_helper->layout->setLayout( 'admin' );
        $this->_urlParams();
    }

    /*     * **********************************
     * 	    INTERFACE
     * ********************************** */

    public function addCategoryAction() {
        $this->_category_add();
    }

    public function delCategoryAction() {
        $this->_category_del();
    }

    public function editCategoryAction() {
        $this->_category_edit();
    }

    public function categoriesAction() {
        $this->_categories();
    }

    public function photosAction() {
        $this->_photos();
    }

    public function addPhotoAction() {
        $this->_photo_add();
    }

    public function loadPhotosAction() {
        $this->_photo_load();
    }

    public function delPhotoAction() {
        $this->_photo_del();
    }

    public function editPhotoAction() {
        $this->_photo_edit();
    }

    /*     * **********************************
     * 	    IMPLEMENTS
     * ********************************** */

    protected function _categories() {
        $id = $this->_params[ 'id' ] ? $this->_params[ 'id' ] : 0;
        $categories = $this->_categoryModel->getByParent( $id );
        $this->_paginatorInit( $categories );

        $category = $this->_categoryModel->getCategory( $this->_params[ 'id' ] );
        $this->view->assign( 'category', $category );

        $this->view->assign( 'params', $this->_params );
    }

    protected function _category_add() {
        $form = new Gallery_Form_Category( null, $this->_params[ 'id' ] );
        $this->view->assign( 'form', $form );

        if ( $this->_request->isPost() ) {
            $formData = $this->_request->getPost();
            if ( $form->isValid( $formData ) ) {
                try {
                    // category image
                    $imageHelper = new Model_Images_GalleryCategory();
                    $formData['image'] = $imageHelper->filter($_FILES['image']['tmp_name']);

                    // transliterate alias for category
                    if (empty($formData['alias']))
                    {
                        $modelTransliterate = new Model_Transliterate();
                        $formData['alias'] = $modelTransliterate->transliterate($formData['title']);
                    }

                    $this->_categoryModel->add( $formData );

                    $this->_helper->flashMessenger->setNamespace( 'messages' )
                        ->addMessage( $this->view->translate( 'categoryAddedSuccess' ) );
                    if ( $this->_params[ 'id' ] ) {
                        $this->_helper->redirector( 'categories', 'admin', 'gallery', array( 'id' => $this->_params[ 'id' ] ) );
                    }
                    $this->_helper->redirector( 'categories', 'admin', 'gallery' );
                } catch ( Exception $e ) {
                    $this->_helper->flashMessenger->setNamespace( 'errorMessages' )
                        ->addMessage( $this->view->translate( 'categoryAddedFailure' ) )
                        ->addMessage( $e->getMessage() );
                }
            } else {
                $this->_helper->flashMessenger->setNamespace( 'warningMessages' )
                    ->addMessage( $this->view->translate( 'fieldsUncorrectfill' ) );
                $form->populate( $formData );
            }
        }
    }

    protected function _category_edit() {
        $category = $this->_categoryModel->getCategory( $this->_params[ 'id' ] );

        $form = new Gallery_Form_Category( $category );
        $this->view->assign( 'form', $form );

        if ( $this->_request->isPost() ) {
            $formData = $this->_request->getPost();
            if ( $form->isValid( $formData ) ) {
                try {
                    // category image
                    $imageHelper = new Model_Images_GalleryCategory();
                    $formData['image'] = $imageHelper->filter($_FILES['image']['tmp_name']);

                    // transliterate alias for category
                    if (empty($formData['alias']))
                    {
                        $modelTransliterate = new Model_Transliterate();
                        $formData['alias'] = $modelTransliterate->transliterate($formData['title']);
                    }

                    $this->_categoryModel->edit( $category[ db_PhotoCategory::_ID ], $formData );

                    $this->_helper->flashMessenger->setNamespace( 'messages' )
                        ->addMessage( $this->view->translate( 'categoryEditedSuccess' ) );
                    $this->_helper->redirector( 'categories', 'admin', 'gallery', array( db_PhotoCategory::_ID => $formData[ db_PhotoCategory::_PID ] ) );
                } catch ( Exception $e ) {
                    $this->_helper->flashMessenger->setNamespace( 'errorMessages' )
                        ->addMessage( $this->view->translate( 'categoryEditedFailure' ) )
                        ->addMessage( $e->getMessage() );
                }
            } else {
                $this->_helper->flashMessenger->setNamespace( 'warningMessages' )
                    ->addMessage( $this->view->translate( 'fieldsUncorrectfill' ) );
                $form->populate( $formData );
            }
        }
    }

    protected function _category_del() {
        $categoryId = $this->_params[ 'id' ];
        $category = $this->_categoryModel->getCategory( $categoryId );
        $this->_checkEmptyCategory( $category );

        $photos = $this->_photoModel->getByCategory( $categoryId );

        if ( empty( $photos ) ) {
            $this->_category_del_simple( $category );
        } else {
            $this->_category_del_choose( $category, $photos );
        }
    }

    protected function _category_del_simple( $category ) {
        $this->_categoryModel->delete( $category );
        $this->_helper->flashMessenger->setNamespace( 'messages' )
            ->addMessage( $this->view->translate( 'categoryDeletedSuccess' ) );
        $this->_helper->redirector( 'categories', 'admin', 'gallery', array( db_PhotoCategory::_ID => $category[ db_PhotoCategory::_PID ] ) );
    }

    protected function _category_del_choose( $category, $photos ) {
        $form = new Gallery_Form_CategoryDelete( $category );
        $this->view->assign( 'form', $form );

        if ( $this->_request->isPost() ) {

            $formData = $this->_request->getPost();
            if ( $form->isValid( $formData ) ) {
                try {
                    if ( isset( $formData[ 'remove' ] ) ) {
                        $this->_photoModel->deleteArray( $photos );
                        $this->_categoryModel->delete( $category );

                        $this->_helper->flashMessenger->setNamespace( 'messages' )
                            ->addMessage( $this->view->translate( 'categoryPhotosRemovedSuccess' ) );
                    }
                    if ( isset( $formData[ 'move' ] ) ) {
                        $this->_relationsModel->changeCategory( $category[ db_PhotoCategory::_ID ], $formData[ db_Photo_Relations::_CATEGORY_ID ] );
                        $this->_categoryModel->delete( $category );

                        $this->_helper->flashMessenger->setNamespace( 'messages' )
                            ->addMessage( $this->view->translate( 'categoryPhotosReplacedSuccess' ) );
                    }
                } catch ( Exception $e ) {
                    $this->_helper->flashMessenger->setNamespace( 'errorMessages' )
                        ->addMessage( $this->view->translate( 'categoryDeletedFailure' ) )
                        ->addMessage( $e->getMessage() );
                }
                $this->_helper->redirector( 'categories', 'admin', 'gallery', array( db_PhotoCategory::_ID => $category[ db_PhotoCategory::_PID ] ) );
            }
        }
    }

    protected function _checkEmptyCategory( $category ) {
        if ( empty( $category ) ) {
            $this->_helper->flashMessenger->setNamespace( 'errorMessages' )
                ->addMessage( $this->view->translate( 'categoryNotFound' ) );
            $this->_helper->redirector( 'categories', 'admin', 'gallery' );
        }
    }

    protected function _photos() {
        $photos = $this->_photoModel->getByCategory( $this->_params[ 'id' ] );
        $this->_paginatorInit( $photos );

        $category = $this->_categoryModel->getCategory( $this->_params[ 'id' ] );
        $this->view->assign( 'category', $category );

        $this->view->assign( 'params', $this->_params );
    }

    protected function _photo_add() {
        $this->_redirectIfEmptyCategories();

        $form = new Gallery_Form_Photo( null, $this->_params[ db_Photo_Relations::_CATEGORY_ID ] );
        $this->view->assign( 'form', $form );

        if ( $this->_request->isPost() ) {
            $formData = $this->_request->getPost();
            if ( $form->isValid( $formData ) ) {
                try {
                    // uploading catalog image
                    if ( !empty( $_FILES[ 'image' ][ 'name' ] ) ) {
                        $formData[ db_Photos::_SRC ] = $this->_helper->fileUploader->uploadFile(
                            $_FILES[ 'image' ][ 'tmp_name' ], $_FILES[ 'image' ][ 'name' ], 'images' . DIRECTORY_SEPARATOR . 'photos'
                        );
                        // Cut and save product cut image
                        $formData[ db_Photos::_CUTSRC ] = $this->_helper->fileUploader->cutImage(
                            $formData[ db_Photos::_SRC ], 'images' . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR . 'cut', 220
                        );
                    }

                    $this->_photoModel->add( $formData );

                    $this->_helper->flashMessenger->setNamespace( 'messages' )
                        ->addMessage( $this->view->translate( 'photoAddedSuccess' ) );
                    $this->_helper->redirector( 'photos', 'admin', 'gallery', array( db_PhotoCategory::_ID => $formData[ db_Photo_Relations::_CATEGORY_ID ] ) );
                } catch ( Exception $e ) {
                    $this->_helper->flashMessenger->setNamespace( 'errorMessages' )
                        ->addMessage( $this->view->translate( 'photoAddedFailure' ) )
                        ->addMessage( $e->getMessage() );
                }
            } else {
                $this->_helper->flashMessenger->setNamespace( 'warningMessages' )
                    ->addMessage( $this->view->translate( 'fieldsUncorrectfill' ) );
                $form->populate( $formData );
            }
        }
    }

    protected function _photo_load() {
        $this->_redirectIfEmptyCategories();

        $form = new Gallery_Form_Photos();
        $this->view->assign( 'form', $form );

        if ( $this->_request->isPost() ) {
            $formData = $this->_request->getPost();
            if ( $form->isValid( $formData ) ) {
                try {
                    // uploading catalog image
                    if ( !empty( $_FILES[ 'image' ][ 'name' ][ 0 ] ) ) {
                        for ( $i = 0; $i < count( $_FILES[ 'image' ][ 'name' ] ); $i++ ) {
                            $formData[ db_Photos::_SRC ] = $this->_helper->fileUploader->uploadFile(
                                $_FILES[ 'image' ][ 'tmp_name' ][ $i ], $_FILES[ 'image' ][ 'name' ][ $i ],
                                'images' . DIRECTORY_SEPARATOR . 'photos', $_FILES[ 'image' ][ 'size' ][ $i ]
                            );
                            // Cut and save product cut image
                            $formData[ db_Photos::_CUTSRC ] = $this->_helper->fileUploader->cutImage(
                                $formData[ db_Photos::_SRC ], 'images' . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR . 'cut', 220
                            );
                            $this->_photoModel->add( $formData );
                        }
                    }

                    $this->_helper->flashMessenger->setNamespace( 'messages' )
                        ->addMessage( $this->view->translate( 'photoLoadedSuccess' ) );
                    $this->_helper->redirector( 'photos', 'admin', 'gallery', array( db_PhotoCategory::_ID => $formData[ db_Photo_Relations::_CATEGORY_ID ] ) );
                } catch ( Exception $e ) {
                    $this->_helper->flashMessenger->setNamespace( 'errorMessages' )
                        ->addMessage( $this->view->translate( 'photoLoadedFailure' ) )
                        ->addMessage( $e->getMessage() );
                }
            } else {
                $this->_helper->flashMessenger->setNamespace( 'warningMessages' )
                    ->addMessage( $this->view->translate( 'fieldsUncorrectfillImages' ) );
                $form->populate( $formData );
            }
        }
    }

    protected function _photo_edit() {
        $photo = $this->_photoModel->getCategory( $this->_params[ 'id' ] );
        $relation = $this->_relationsModel->getByPhotoId( $this->_params[ 'id' ] );

        $form = new Gallery_Form_Photo( $photo, $relation[ db_Photo_Relations::_CATEGORY_ID ] );
        $this->view->assign( 'form', $form );

        if ( $this->_request->isPost() ) {
            $formData = $this->_request->getPost();
            if ( $form->isValid( $formData ) ) {
                try {
                    // uploading catalog image
                    if ( !empty( $_FILES[ 'image' ][ 'name' ] ) ) {
                        $formData[ db_Photos::_SRC ] = $this->_helper->fileUploader->uploadFile(
                            $_FILES[ 'image' ][ 'tmp_name' ], $_FILES[ 'image' ][ 'name' ], 'images' . DIRECTORY_SEPARATOR . 'photos'
                        );
                        // Cut and save product cut image
                        $formData[ db_Photos::_CUTSRC ] = $this->_helper->fileUploader->cutImage(
                            $formData[ db_Photos::_SRC ], 'images' . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR . 'cut', 220
                        );
                    }

                    $this->_photoModel->edit( $photo, $relation, $formData );

                    $this->_helper->flashMessenger->setNamespace( 'messages' )
                        ->addMessage( $this->view->translate( 'photoEditedSuccess' ) );
                    $this->_helper->redirector( 'photos', 'admin', 'gallery', array( db_PhotoCategory::_ID => $formData[ db_Photo_Relations::_CATEGORY_ID ] ) );
                } catch ( Exception $e ) {
                    $this->_helper->flashMessenger->setNamespace( 'errorMessages' )
                        ->addMessage( $this->view->translate( 'photoEditedFailure' ) )
                        ->addMessage( $e->getMessage() );
                }
            } else {
                $this->_helper->flashMessenger->setNamespace( 'warningMessages' )
                    ->addMessage( $this->view->translate( 'fieldsUncorrectfill' ) );
                $form->populate( $formData );
            }
        }
    }

    protected function _photo_del() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );
        $relation = $this->_relationsModel->getByPhotoId( $this->_params[ 'id' ] );

        try {
            $this->_photoModel->delete( $this->_params[ 'id' ], $relation );

            $this->_helper->flashMessenger->setNamespace( 'messages' )
                ->addMessage( $this->view->translate( 'photoDeletedSuccess' ) );
        } catch ( Exception $e ) {
            $this->_helper->flashMessenger->setNamespace( 'errorMessages' )
                ->addMessage( $this->view->translate( 'photoDeletedFailure' ) )
                ->addMessage( $e->getMessage() );
        }

        $this->_helper->redirector( 'photos', 'admin', 'gallery', array( db_PhotoCategory::_ID => $relation[ db_Photo_Relations::_CATEGORY_ID ] ) );
    }

    protected function _paginatorInit( $items ) {
        $paginator = Zend_Paginator::factory( $items );
        $paginator->setItemCountPerPage( 15 );
        $paginator->setCurrentPageNumber( $this->_params[ 'page' ] );

        $this->view->assign( 'paginator', $paginator );
    }

    protected function _redirectIfEmptyCategories() {
        $categories = $this->_categoryModel->getByParent( 0 );
        if ( empty( $categories ) ) {
            $this->_helper->flashMessenger->setNamespace( 'errorMessages' )
                ->addMessage( $this->view->translate( 'pleaseAddCategories' ) );
            $this->_helper->redirector( 'categories', 'admin', 'gallery' );
        }
    }

    protected function _urlParams() {
        $params = Zend_Controller_Front::getInstance()->getRequest()->getUserParams();

        if ( isset( $params[ 'page' ] ) ) {
            $this->_params[ 'page' ] = $params[ 'page' ];
        } else {
            $this->_params[ 'page' ] = 1;
        }
        if ( isset( $params[ 'id' ] ) ) {
            $this->_params[ 'id' ] = $params[ 'id' ];
        } else {
            $this->_params[ 'id' ] = null;
        }
        if ( isset( $params[ db_Photo_Relations::_CATEGORY_ID ] ) ) {
            $this->_params[ db_Photo_Relations::_CATEGORY_ID ] = $params[ db_Photo_Relations::_CATEGORY_ID ];
        } else {
            $this->_params[ db_Photo_Relations::_CATEGORY_ID ] = null;
        }
    }

}
