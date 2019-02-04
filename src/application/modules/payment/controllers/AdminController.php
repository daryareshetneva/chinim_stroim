<?php

class Payment_AdminController extends Zend_Controller_Action {
    
    public function init() {
        $this->_helper->layout->setLayout( 'admin' );
    }

    public function indexAction() {
        $trxTable = new Payment_Model_DbTable_Transaction();
        $userModel = new User_Model_Users();
        $trxs = $trxTable->trxGetAll();

        $result = array( );
        foreach ( $trxs as $trx ) {
            $user = $userModel->getUserById( $trx[ 'userId' ] );
            $trx[ 'fio' ] = $user[ 'fio' ];
            $trx[ 'email' ] = $user[ 'email' ];
            $result[ ] = $trx;
        }

        $page = $this->_request->getParam( 'page', 1 );

        $paginator = Zend_Paginator::factory( $result );
        $paginator->setItemCountPerPage( 30 );
        $paginator->setCurrentPageNumber( $page );

        $this->view->assign( 'paginator', $paginator );
    }

    public function historyAction() {
        $trxHistoryTable = new Payment_Model_DbTable_TrxHistory();
        $trxHistory = $trxHistoryTable->getAll();

        $page = $this->_request->getParam( 'page', 1 );

        $paginator = Zend_Paginator::factory( $trxHistory );
        $paginator->setItemCountPerPage( 30 );
        $paginator->setCurrentPageNumber( $page );

        $this->view->assign( 'paginator', $paginator );
    }

}
