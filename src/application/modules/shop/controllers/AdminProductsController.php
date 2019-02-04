<?php

class Shop_AdminProductsController extends Zend_Controller_Action {
    protected $_action  = '';
    private $_product = null;
    private $_table = null;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->_action = $this->_request->getActionName();

        if (in_array($this->_action, array('edit', 'delete', 'product-images', 'add-product-image',
            'delete-product-image', 'product-properties', 'add-product-property', 'delete-product-property'))) {
            $this->_table = new Shop_Model_DbTable_Products();

            $this->_product = $this->_table->find($this->_getParam('id'))->current();
            if (!$this->_product){
                $this->_productNotFound();
            }
        }
    }

    public function indexAction()
    {
        $page = $this->_request->getParam('page', 1);
        $productModel = new Shop_Model_Products();
        $products = $productModel->getAllProductsForAdmin();

        $paginator = Zend_Paginator::factory($products);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);

        $this->view->assign('products', $products);
        $this->view->assign('paginator', $paginator);
    }

    public function addAction()
    {
        $productTable = new Shop_Model_DbTable_Products();

        $categoryModel = new Shop_Model_Categories();
        $allCategories = $categoryModel->getSortedAllCategories();

        $product = $productTable->createRow();

        $allCategories = ['0' => 'Выберите категорию'] + $allCategories;

        $form = new Shop_Form_AdminProduct($product, $allCategories);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    $imageHelper = new Model_Images_Product();
                    $formData['image'] = $imageHelper->filter($_FILES['mainImage']['tmp_name']);
                    if (empty($formData['alias']))
                    {
                        $translit = new Model_Transliterate();
                        $formData['alias'] = $translit->transliterate($formData['title']);
                    }

                    $formData['titleSearch'] = $this->trimForSearch($formData['title']);

                    $product->setFromArray($formData);
                    $product->save();

                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage(sprintf($this->view->translate('added'), $formData['title']));

                    $this->_helper->redirector('index', 'admin-products', 'shop');
                } catch (Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                    $form->populate($formData);
                }
            }
        }

        $this->_helper->viewRenderer('update');
        $this->view->assign('action', $this->_action);
        $this->view->assign('form', $form);
    }

    public function editAction()
    {
        $categoryModel = new Shop_Model_Categories();
        $allCategories = $categoryModel->getSortedAllCategories();

        $allCategories = ['0' => 'Выберите категорию'] + $allCategories;

        $form = new Shop_Form_AdminProduct($this->_product, $allCategories);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    if (!empty($_FILES['mainImage'])) {
                        $imageHelper = new Model_Images_Product();
                        $formData['image'] = $imageHelper->filter($_FILES['mainImage']['tmp_name']);
                    }
                    if (empty($formData['alias']))
                    {
                        $translit = new Model_Transliterate();
                        $formData['alias'] = $translit->transliterate($formData['title']);
                    }

                    $productTitle = $this->_table->getProductTitleById($this->_getParam('id'));
                    if ($productTitle != $formData['title']){
                        $formData['titleSearch'] = $this->trimForSearch($formData['title']);
                    }

                    $this->_product->setFromArray($formData);
                    $this->_product->save();

                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage(sprintf($this->view->translate('added'), $formData['title']));

                    $this->_helper->redirector('index', 'admin-products', 'shop');
                } catch (Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                    $form->populate($formData);
                }
            }
        }

        $this->_helper->viewRenderer('update');
        $this->view->assign('action', $this->_action);
        $this->view->assign('form', $form);
    }

    public function productImagesAction() {
        $productImagesTable = new Shop_Model_DbTable_ProductImages();
        $images = $productImagesTable->getImagesByProductId($this->_product->id);

        $this->view->assign('images', $images);
        $this->view->assign('product', $this->_product);
    }

    public function addProductImageAction() {
        $form = new Shop_Form_AdminProductImage();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $imageHelper = new Model_Images_Product();
                $image = $imageHelper->filter($_FILES['image']['tmp_name'], $_FILES['image']['name']);
                $productImageTable = new Shop_Model_DbTable_ProductImages();
                $productImage = $productImageTable->createRow();
                $productImage->productId = $this->_product->id;
                $productImage->image = $image;
                $productImage->save();

                $this->_helper->flashMessenger->setNamespace('messages')
                    ->addMessage(sprintf($this->view->translate('imageAdded')));

                $this->_helper->redirector('product-images', 'admin-products', 'shop', ['id' => $this->_product->id]);
            }
        }

        $this->view->assign('form', $form);
    }

    public function deleteProductImageAction() {
        try {
            $productImageTable = new Shop_Model_DbTable_ProductImages();
            $image = $productImageTable->find($this->_request->getParam('imageId'))->current();
            $image->delete();
            $this->_helper->flashMessenger->setNamespace('messages')
                ->addMessage(sprintf($this->view->translate('imageDeleted')));
        } catch (Exception $e) {
            $this->_helper->flashMessenger->setNamespace('errorMessages')
                ->addMessage(sprintf($this->view->translate('imageNotFound')));
        }
        $this->_helper->redirector('product-images', 'admin-products', 'shop', ['id' => $this->_product->id]);
    }

    /**
     * TODO! Добавить реализацию
     */
    public function changeProductImagePositionAction() {

    }

    public function productPropertiesAction() {
        $filterProductRelationsTable = new Shop_Model_DbTable_FilterProductsRelations();
        $properties = $filterProductRelationsTable->getFiltersByProductId($this->_product->id);

        $this->view->assign('properties', $properties);
        $this->view->assign('product', $this->_product);
    }

    public function addProductPropertyAction() {
        $filterProductRelationsTable = new Shop_Model_DbTable_FilterProductsRelations();
        $filtersTable = new Shop_Model_DbTable_Filters();
        $filters = $filtersTable->getPairs();
        $existFilterElements = $filterProductRelationsTable->getProductFilterElementsPairs($this->_product->id);
        $productUsedFilters = $filterProductRelationsTable->getProductUsedFilters($this->_product->id);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if (!empty($formData['filterValue'])) {
                $relation = $filterProductRelationsTable->createRow();
                $relation->productId = $this->_product->id;
                $relation->filterElementId = $formData['filterValue'];
                $relation->save();

                $this->_helper->flashMessenger->setNamespace('messages')
                    ->addMessage($this->view->translate('relationAdded'));
                $this->_helper->redirector('product-properties', 'admin-products', 'shop', ['id' => $this->_product->id]);
            } else {
                $this->view->assign('error', $this->view->translate('propertyNotSelected'));
            }
        }

        $this->view->assign('filters', $filters);
        $this->view->assign('existFilterElements', json_encode($existFilterElements));
        $this->view->assign('usedFilters', $productUsedFilters);
    }

    public function deleteProductPropertyAction() {
        $propertyId = $this->_request->getParam('propertyId');
        $filterProductRelationsTable = new Shop_Model_DbTable_FilterProductsRelations();
        $relation = $filterProductRelationsTable->find($propertyId)->current();
        if (!$relation) {
            $this->_relationNotFound();
        }
        $relation->delete();
        $this->_helper->flashMessenger->setNamespace('messages')
            ->addMessage($this->view->translate('relationDeleted'));
        $this->_helper->redirector('product-properties', 'admin-products', 'shop', ['id' => $this->_product->id]);
    }

    public function deleteAction()
    {
        $this->_table->moveProductToArchive($this->_getParam('id'));
        $this->_helper->flashMessenger->setNamespace('messages')
            ->addMessage($this->view->translate('delete'));
        $this->_helper->redirector('index', 'admin-products', 'shop');
    }

    protected function _productNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage($this->view->translate('productNotFound'));
        $this->_helper->redirector('index', 'admin-products', 'shop');
    }

    private function trimForSearch($string)
    {
        $pattern = "/[^a-zа-яёй0-9+]+/iu";
        return preg_replace($pattern, "", mb_strtolower($string, 'UTF-8'));
    }
}