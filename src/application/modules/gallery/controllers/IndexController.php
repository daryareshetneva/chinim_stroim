<?php

class Gallery_IndexController extends ItRocks_Controller_Action {

    protected $_categoryModel;
    protected $_photoModel;
    protected $_relationsModel;
    protected $_params;

    public function __construct( \Zend_Controller_Request_Abstract $request, \Zend_Controller_Response_Abstract $response, array $invokeArgs = array( ) ) {
        parent::__construct( $request, $response, $invokeArgs );

        $this->_categoryModel = new Gallery_Model_Category();
        $this->_photoModel = new Gallery_Model_Photo();
        $this->_relationsModel = new Gallery_Model_Relations();

        $this->_urlParams();
    }

    /*     * **********************************
     * 	    INTERFACE
     * ********************************** */

    public function indexAction() {
        $this->_categories();
    }

    public function sliderAction() {
        $id = 1;
        $photos = $this->_photoModel->getByCategory( $id );

        $this->view->assign('photos', $photos);
    }

    /*     * **********************************
     * 	    IMPLEMENTS
     * ********************************** */

    protected function _categories() {
        $id = $this->_params[ 'id' ] ? $this->_params[ 'id' ] : 0;
        try{
            $categories = $this->_categoryModel->getByParent( $id );
            $this->_paginatorInit( $categories );

            $category = $photos = null;
            if ($id) {
                $category = $this->_categoryModel->getCategory( $id );
                $photos = $this->_photoModel->getByCategory( $id );
                if (empty($category['metaTitle'])){
                    $category['metaTitle'] = $category['title'];
                }
            }
            $this->_addMeta($category['metaTitle'], $category['metaDesc']);
            $this->view->assign( 'photos', $photos );
            $this->view->assign( 'category', $category );
            $this->view->assign( 'params', $this->_params );
        } catch (Exception $e){
            echo $e->getMessage();
        }
    }

    protected function _photo() {
        $id = $this->_params[ 'id' ] ? $this->_params[ 'id' ] : 0;
        try{
            $photo = $this->_photoModel->getCategory( $id );
            var_dump($photo);

            list( $prev, $next, $categoryId ) = $this->_photoModel->getPrevAndNextAndCategoryId( $photo );

            $this->view->assign( 'photo', $photo );

            $this->view->assign( 'categoryId', $categoryId );
            $this->view->assign( 'prev', $prev );
            $this->view->assign( 'next', $next );
            $this->view->assign( 'params', $this->_params );
        } catch (Exception $e){
            echo $e->getMessage();
        }
    }

    protected function _checkEmptyPhoto( $photo ) {
        if ( empty( $photo ) ) {
            $this->_helper->flashMessenger->setNamespace( 'errorMessages' )
                ->addMessage( $this->view->translate( 'photoNotFound' ) );
            $this->_helper->redirector( 'index', 'index', 'gallery' );
        }
    }

    protected function _paginatorInit( $items ) {
        $paginator = Zend_Paginator::factory( $items );
        $paginator->setItemCountPerPage( 20 );
        $paginator->setCurrentPageNumber( $this->_params[ 'page' ] );

        $this->view->assign( 'paginator', $paginator );
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
    }
}
