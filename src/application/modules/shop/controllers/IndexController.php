<?php
class Shop_IndexController extends ItRocks_Controller_Action {

    protected $_cart = [];
    protected $_price = 0;
    protected $_count = 0;
    protected $_userTable = null;
    protected $_auth = false;
    protected $_user = null;
    protected $_userData = [];

    public function init() {
        if (!isset($_COOKIE['cart'])) {
            setcookie('cart', json_encode($this->_cart), time()+$this->_getCartCookieLife(), '/');
        }
        $tagsModel = new Model_Tags();
        $tags = $tagsModel->getTagsByMCA(
            $this->_request->getModuleName(),
            $this->_request->getControllerName(),
            $this->_request->getActionName()
        );

        $this->_addMeta($tags['metaTitle'], $tags['metaDescription']);
        $this->view->assign('title', $tags['title']);
    }

    public function indexAction() {
        $page = $this->_request->getParam('page', 1);
        $filters = $this->_getFilters();

        if (!isset($filters['view'])) {
            if ($_SESSION['view'] == 'list') {
                $this->_helper->viewRenderer('index-table');
            } else {
                $this->_helper->viewRenderer('index');
            }

        } else {
            if ($filters['view'] == 'list' ) {
                $_SESSION['view'] = 'list';
                $this->_helper->viewRenderer('index-table');
            } else {
                $_SESSION['view'] = 'block';
                $this->_helper->viewRenderer('index');
            }
        }

        unset($filters['view']);
        unset($filters['page']);

        $productModel = new Shop_Model_Products();
        $categoryModel = new Shop_Model_Categories();
        $filtersModel = new Shop_Model_Filters();
        $availableFilters = $filtersModel->getAvailableFiltersWithElements();

        $allCategories = $categoryModel->getAllCategories();

        $alias = $this->_getParam('alias');
        $title = 'Весь каталог';
        $products = [];

        if ($alias === null || 'catalog' == $alias)
        {
            $products = $productModel->getAllProducts($filters);
        } else {
            $category = $categoryModel->getCategoryIdByAlias($alias);
            if (!empty($category))
            {
                $title = $category['title'];
                $categories = $categoryModel->getTreeCategoriesById($category['id']);
                $categories[$category['id']] = '';

                $products = $productModel->getProductsByCategories($categories, $filters);
            } else {
                $this->_productNotFound();
            }
        }

        $paginator = Zend_Paginator::factory($products);
        if ($_SESSION['view'] == 'block') {
            $paginator->setItemCountPerPage(20);
        } else {
            $paginator->setItemCountPerPage(30);
        }
        $paginator->setCurrentPageNumber($page);
        $currentPageNumber = $paginator->getCurrentPageNumber();
        $requestPageNumber = (int)$this->_request->getParam('page');

        if (($currentPageNumber < $requestPageNumber) || (!is_int($requestPageNumber))) {
            $this->_productNotFound();
        }

        $this->view->assign('countProducts', count($products));
        $this->view->assign('title', $title);
        $this->view->assign('alias', $alias);
        $this->view->assign('allCategories', $allCategories);
        $this->view->assign('paginator', $paginator);
        $this->view->assign('availableFilters', $availableFilters);
        $this->view->assign('filters', $filters);
        $this->_addMeta($title, '');
    }

	public function productAction() {
        $alias = $this->_request->getParam('alias', null);

        if (!$alias) {
            $this->_productNotFound();
        }

        $productsTable = new Shop_Model_DbTable_Products();
        $productImagesTable = new Shop_Model_DbTable_ProductImages();
        $product = $productsTable->getByAlias($alias);

        if (!$product) {
            $this->_productNotFound();
        }
        $images = $productImagesTable->getImagesByProductId($product->id);

        $categoryModel = new Shop_Model_Categories();

        $allCategories = $categoryModel->getAllCategories();

        $categoriesTable = new Shop_Model_DbTable_Categories();
        $category = $categoriesTable->find($product->categoryId)->current();

        $filterElementsTable = new Shop_Model_DbTable_FilterElements();
        $productFilters = $filterElementsTable->getFilterWithElementsByProductId($product->id);

        $this->view->assign('product', $product);
        $this->view->assign('category', $category);
        $this->view->assign('allCategories', $allCategories);
        $this->view->assign('images', $images);
        $this->view->assign('productFilters', $productFilters);
        $this->_addMeta($product->metaTitle, $product->metaDescription);
    }

    public function cartAction()
    {
        if (isset($_COOKIE['cart'])){
            $settingsModel = new Model_Settings();
            $discount   = $settingsModel->getSettings(['discountOne', 'discountTwo']);

            // получаем информацию по товарам с корзины
            $productModel = new Shop_Model_Products();
            $this->_cart = json_decode($_COOKIE['cart'], true);
            $products = $productModel->getProductsCart($this->_cart);
            // итоговая цена за все товары
            $total = $this->_getCartInfo($this->_cart);
            // итоговая без скидки

            $this->view->assign('dicount', $discount);
            $this->view->assign('products', $products);
            $this->view->assign('cart', $this->_cart);
            $this->view->assign('totals', $total);
        }
    }

    public function addToCartAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->_request->isPost())
        {
            $data = $this->_request->getPost();
            if (isset($_COOKIE['cart']))
            {
                // получаем все товары с корзины
                $this->_cart = json_decode($_COOKIE['cart'], true);

                if (isset($this->_cart[$data['id']]) && ($data['action'] == 'add'))
                {
                    // если товар уже был добавлен, суммируем колличество
                    $data['amount'] = $this->_cart[$data['id']] + $data['amount'];
                }
                // удаление товара из корзины
                if ($data['action'] == 'remove')
                {
                    unset($this->_cart[$data['id']]);
                }
                // добавление и обновление товара в корзине
                if ($data['action'] == 'add' || $data['action'] == 'update')
                {
                    $this->_cart[$data['id']] = $data['amount'];
                }
            }

            // общая цена и колличество товаров в корзине
            $cart = $this->_getCartInfo($this->_cart);

            setcookie('cart', json_encode($this->_cart), time()+$this->_getCartCookieLife(), '/');
            echo Zend_Json::encode($cart);
        } else {
            return false;
        }
    }

    private function _getCartInfo($cart)
    {
        // получаем информацию по товарам с корзины
        $productModel = new Shop_Model_Products();
        $products = $productModel->getProductsCart($this->_cart);
        $totalWithoutDiscount = 0;

        // суммируем цену
        foreach ($cart as $key => $value)
        {
            $this->_count += $value;
        }
        // суммируем колличество
        foreach ($products as $item)
        {
            $price = $item['price'];
            if ((int)$item['discount'] > 0){
                $price = $item['discount'];
            }
            $this->_price += $price * (int)$this->_cart[$item['id']];
            $totalWithoutDiscount += $item['price'] * (int)$this->_cart[$item['id']];
        }
        return ['price' => $this->_price, 'count' => $this->_count, 'priceWithoutDiscount' => $totalWithoutDiscount];
    }

    public function orderAction()
    {
        // проверяем, есть ли товары
        if (isset($_COOKIE['cart']))
        {
            $this->_auth = Zend_Auth::getInstance();
            // проверяем, авторизован ли пользователь
            if ($this->_auth->hasIdentity()) {
                // объект пользователя
                $this->_user = $this->_auth->getIdentity();
                //данные пользователя
                $this->_userTable = new User_Model_DbTable_Users();
                $this->_userData = $this->_userTable->getUserByEmailForOrder($this->_user->email);
            }

            // итоговая цена за все товары
            $this->_cart = json_decode($_COOKIE['cart'], true);
            $totals =$this->_getCartInfo($this->_cart);

            $orderTable = new Shop_Model_DbTable_Orders();
            $order = $orderTable->createRow();

            $form = new Shop_Form_Order($order, $this->_userData);

            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    try {
                        if ($this->_auth->hasIdentity())
                        {
                            $formData['userId'] = $this->_userData['id'];
                        }
                        if (isset($formData['reg']) && $formData['reg'] == 'on')
                        {
                            $formData['password'] = md5($formData['password']);
                            // создаем нового пользователя
                            $this->_userTable = new User_Model_DbTable_Users();
                            $formData['userId'] = $this->_userTable->addUserOrder($formData, date('Y-m-d H:i:s'));
                        }
                        if (1 == $formData['userType']) {
                            if (!empty($_FILES['requisites']['tmp_name'])) {
                                $fileUploadModel = new Model_FileUpload();
                                $formData['requisites'] = $fileUploadModel->uploadRequisites($_FILES['requisites']['tmp_name'], $_FILES['requisites']['name']);
                            }
                        }

                        $formData['name'] = $formData['firstName'].' '.$formData['lastName'].' '.$formData['patronymic'];
                        $formData['date'] = date('Y-m-d H:i:s');
                        $order->setFromArray($formData);

                        $idOrder = $order->save();
                        // список товаров в корзине у пользователя (вместе с колличеством)
                        $products = $this->_getProductsCartInfo();
                        // заносим информацию о заказе в Shop_Ordered_Products
                        $orderedProducts = new Shop_Model_OrderedProducts();
                        $orderedProducts->insertOrderedProducts($idOrder, $products);

                        // очищаем корзину
                        $this->_clearCart();

                        $this->_helper->redirector('order-success', 'index', 'shop');
                    } catch (Exception $e) {

                        $this->view->assign('error', $e->getMessage());
                    }
                }
            }

            $this->view->assign('auth', $this->_auth->hasIdentity());
            $this->view->assign('form', $form);
            $this->view->assign('totals', $totals);
        }
    }

    public function orderSuccessAction()
    {

    }

    private function _getProductsCart(){
        if (isset($_COOKIE['cart'])){
            $this->_cart = json_decode($_COOKIE['cart'], true);
        }
        return $this->_cart;
    }

    private function _getProductsCartInfo(){
        $productsArray = $this->_getProductsCart();

        $productModel = new Shop_Model_Products();
        $products = $productModel->getProductsForCart($productsArray);
        return $products;
    }

    private function _clearCart(){
        setcookie('cart', '', time()-$this->_getCartCookieLife(),'/');
    }

    private function _getCartCookieLife(){
        return $this->getInvokeArg('bootstrap')->getOption('cartCookieLife');
    }
    /**
     * TODO! Добавить правильный редирект
     */
    private function _productNotFound() {

    }

    private function _getFilters() {
        $params = $this->_request->getParams();
        unset($params['controller']);
        unset($params['action']);
        unset($params['alias']);
        unset($params['action']);
        unset($params['module']);
        return $params;
    }

}