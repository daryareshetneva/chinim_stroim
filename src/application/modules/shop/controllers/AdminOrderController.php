<?php
class Shop_AdminOrderController extends Zend_Controller_Action{

    protected $_action = '';
    protected $_status = 0;
    protected $_orderId = 0;

    public function init(){
        $this->_helper->layout->setLayout('admin');
        $this->_action = $this->_request->getActionName();
    }

    public function indexAction(){
        $this->_status = $this->_request->getParam('status');
        if (isset($this->_status))
        {
            $orderModel = new Shop_Model_Orders();
            $orders = $orderModel->getOrdersByStatus($this->_status);

            $page = $this->_request->getParam('page', 1);
            $paginator = Zend_Paginator::factory($orders);
            $paginator->setItemCountPerPage(5);
            $paginator->setCurrentPageNumber($page);

            $this->view->assign('status', $this->_status);
            $this->view->assign('orders', $orders);
            $this->view->assign('paginator', $paginator);
        }
    }

    public function viewOrderAction(){
        $this->_orderId = $this->_request->getParam('id');
        $orderModel = new Shop_Model_OrderedProducts();
        $orders = $orderModel->getOrderById($this->_orderId);

        $status_array = [
            0 => 'Принят',
            1 => 'В резерве',
            2 => 'Оплачен',
            3 => 'Выдан',
            4 => 'Отменен'
        ];

        $form = new Shop_Form_OrderStatus($orders, $status_array);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    // сохраняем
                    $orderTable = new Shop_Model_DbTable_Orders();
                    $orderTable->updateOrderStatus($this->_orderId, $formData['status']);

                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage($this->view->translate('Изменения были сохранены'));

                    $this->redirect('/shop/admin-order/index/status/0');
                } catch (Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                    $this->_helper->redirector('add', 'admin-categories', 'shop');
                }
            }
        }

        $this->view->assign('form', $form);
        $this->view->assign('orders', $orders);
    }
}
?>