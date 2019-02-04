<?php
class Services_AdminController extends Zend_Controller_Action {
    private $_itemsModel;
    private $_treeModel;
    private $_itemsDbModel;
    private $_treeDbModel;
    protected $_moduleId = null;

    public function init() {
        $this->_helper->layout->setLayout('admin');

        $moduleTable = new Model_DbTable_Modules();
        $module = $moduleTable->getModuleIdByName('services');
        $this->_moduleId = $module['id'];

        $this->_treeModel = new Services_Model_Tree();
        $this->_itemsModel = new Services_Model_Items();
        $this->_treeDbModel = new Services_Model_DbTable_Tree();
        $this->_itemsDbModel = new Services_Model_DbTable_Items();
    }

    // categories

    public function indexAction() {
        $id = $this->getParam('parent', 0);
        $categories = $this->_treeDbModel->getByParent($id);        
        $categoryTitle = $this->_treeDbModel->getNodeTitleById($id);
        $parentCategoryId = $this->_treeDbModel->getNodeParentIdById($id);

        $this->view->assign('categories', $categories);
        $this->view->assign('parent', $id);
        $this->view->assign('parentCategoryId', $parentCategoryId);
        $this->view->assign('categoryTitle', $categoryTitle);
    }

    public function addCategoryAction() {
        $parent = $this->getParam('parent', 0);
        $form = new Services_Form_AddCategory();

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                try {
                    if (empty($data['alias'])) {
                        $transliterate = new Model_Transliterate();
                        $data['alias'] = $transliterate->transliterate($data['title']);
                    }
                    if (!empty($_FILES['categoryPhoto']['tmp_name'])) {
                        $imageHelper = new Services_Model_Images();
                        $data['categoryPhoto'] = $imageHelper->filter($_FILES['categoryPhoto']['tmp_name'], $_FILES['categoryPhoto']['name']);
                    }
                    if ($data['parent_id'] == '0') {
                        $this->_treeDbModel->insertToRoot($data);
                    } else {
                        $this->_treeDbModel->insertToParent($data);
                    }
                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage($this->view->translate('servicesCategoryAdded'));
                    $this->_helper->redirector('index', 'admin', 'services', array('parent' => $parent));
                } catch (Exception $e) {
                    $this->_helper->flashMessenger->setNamespace('errorMessages')
                        ->addMessage($this->view->translate($e));
                    $this->_helper->redirector('index', 'admin', 'services', array('parent' => $parent));
                }
            }
        }

        $form->getElement('parent_id')->setValue($parent);
        $this->view->assign('form', $form);
    }

    public function editCategoryAction() {
        $id = $this->getParam('id');
        $parent = $this->getParam('parent', 0);

        if ($id) {
            $category = $this->_treeDbModel->find($id)->current();
            if ($category) {
                $form = new Services_Form_EditCategory($category);

                if ($this->_request->isPost()) {
                    $data = $this->_request->getPost();

                    if ($form->isValid($data)) {
                        try {
                            if (empty($data['alias'])) {
                                $transliterate = new Model_Transliterate();
                                $data['alias'] = $transliterate->transliterate($data['title']);
                            }
                            if (!empty($_FILES['categoryPhoto']['tmp_name'])) {
                                $imageHelper = new Services_Model_Images();
                                $data['categoryPhoto'] = $imageHelper->filter($_FILES['categoryPhoto']['tmp_name'], $_FILES['categoryPhoto']['name']);
                            }
                            $category->setFromArray($data);
                            $category->save();
                            $this->_helper->flashMessenger->setNamespace('messages')
                                ->addMessage($this->view->translate('servicesCategoryEditSuccess'));
                            $this->_helper->redirector('index', 'admin', 'services', array('parent' => $parent));
                        } catch (Exception $e) {
                            $this->_helper->flashMessenger->setNamespace('errorMessages')
                                ->addMessage($this->view->translate($e));
                            $this->_helper->redirector('index', 'admin', 'services', array('parent' => $parent));
                        }
                    }
                }

                $this->view->assign('form', $form);
            } else {
                $this->_helper->flashMessenger->setNamespace('errorMessages')
                    ->addMessage($this->view->translate('servicesNotFound'));
                $this->_helper->redirector('index', 'admin', 'services', array('parent' => $parent));
            }
        } else {
            $this->_helper->flashMessenger->setNamespace('errorMessages')
                ->addMessage($this->view->translate('servicesNotFound'));
            $this->_helper->redirector('index', 'admin', 'services', array('parent' => $parent));
        }
    }

    public function removeCategoryAction() {
        $id = $this->getParam('id');
        $parent = $this->getParam('parent', 0);

        if ($id) {
            try {
                $services = $this->_treeDbModel->getAllSubItemsById($id);

                if ($services) {
                    $uploaderModel = new Model_Uploader();
                    $uploaderModel->deleteRelationsByMAP($this->_moduleId, '2', $services);
                }

                $this->_treeDbModel->deleteNode($id);
                $this->_itemsDbModel->deleteItemsByCategoryIds($services);
                $this->_helper->flashMessenger->setNamespace('messages')
                    ->addMessage($this->view->translate('servicesCategoryDeleted'));
                $this->_helper->redirector('index', 'admin', 'services', array('parent' => $parent));
            } catch (Exception $e) {
                $this->_helper->flashMessenger->setNamespace('errorMessages')
                    ->addMessage($this->view->translate($e));
                $this->_helper->redirector('index', 'admin', 'services', array('parent' => $parent));
            }
        }

        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage($this->view->translate('servicesNotFound'));
        $this->_helper->redirector('index', 'admin', 'services', array('parent' => $parent));
    }

    // services
    public function listServicesAction() {
        $id = $this->getParam('category', 0);
        $services = $this->_itemsDbModel->getByCategory($id);
        if ($id != 0) {
            $catTitle = $this->_treeDbModel->getNodeTitleById($id);
        } else {
            $catTitle = '';
        }
        $parentCategoryId = $this->_treeDbModel->getNodeParentIdById($id);

        $this->view->assign('services', $services);
        $this->view->assign('categoryTitle', $catTitle);
        $this->view->assign('parentCategoryId', $parentCategoryId);
        $this->view->assign('categoryId', $id);
    }

    public function addServiceAction() {
        $category = $this->getParam('category', 0);

        $form = new Services_Form_AddService();

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                try {
                    $new = $this->_itemsDbModel->createRow();
                    if (empty($data['alias'])) {
                        $transliterate = new Model_Transliterate();
                        $data['alias'] = $transliterate->transliterate($data['title']);
                    }
                    if (!empty($_FILES['serviceMainPhoto']['tmp_name'])) {
                        $imageHelper = new Services_Model_Images();
                        $data['serviceMainPhoto'] = $imageHelper->filter($_FILES['serviceMainPhoto']['tmp_name'], $_FILES['serviceMainPhoto']['name']);
                    }
                    $new->setFromArray($data);
                    $new->save();

                    if (!empty($_FILES['image']['name'][0])) {
                        $fileUpload = new Model_FileUpload();
                        $servicesImagesTable = new Services_Model_DbTable_ServicesImages();

                        for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
                            $filename = $fileUpload->uploadServicePhoto($_FILES['image']['tmp_name'][$i], $_FILES['image']['name'][$i]);
                            $servicesImagesTable->add($filename, $data['alias']);
                        }
                    }

                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage($this->view->translate('servicesAdded'));
                    $this->_helper->redirector('list-services', 'admin', 'services', array('category' => $category));
                } catch (Exception $e) {
                    $this->_helper->flashMessenger->setNamespace('errorMessages')
                        ->addMessage($this->view->translate($e));
                    $this->_helper->redirector('list-services', 'admin', 'services', array('category' => $category));
                }
            }
        }

        $form->getElement('category_id')->setValue($category);
        $this->view->assign('form', $form);
    }

    public function editServiceAction() {
        $id = $this->getParam('id');
        $category = $this->getParam('category', 0);

        // чтобы передать картинки view
        $servicesImagesTable = new Services_Model_DbTable_ServicesImages();


        if ($id) {
            $item = $this->_itemsDbModel->find($id)->current();
            $images = $servicesImagesTable->getImagesByServiceAlias($item['alias']);
            if ($item) {
                $form = new Services_Form_EditService($item);

                if ($this->_request->isPost()) {
                    $data = $this->_request->getPost();
                    if ($form->isValid($data)) {
                        try {
                            if (empty($data['alias'])) {
                                $transliterate = new Model_Transliterate();
                                $data['alias'] = $transliterate->transliterate($data['title']);
                            }
                            if (!empty($_FILES['serviceMainPhoto']['tmp_name'])) {
                                $imageHelper = new Services_Model_Images();
                                $data['serviceMainPhoto'] = $imageHelper->filter($_FILES['serviceMainPhoto']['tmp_name'], $_FILES['serviceMainPhoto']['name']);
                            }
                            $item->setFromArray($data);
                            $item->save();

                            if (!empty($_FILES['image']['name'][0])) {
                                $fileUpload = new Model_FileUpload();

                                for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
                                    $filename = $fileUpload->uploadServicePhoto($_FILES['image']['tmp_name'][$i], $_FILES['image']['name'][$i]);
                                    $servicesImagesTable->add($filename, $data['alias']);
                                }
                            }

                            $this->_helper->flashMessenger->setNamespace('messages')
                                ->addMessage($this->view->translate('servicesEditSuccess'));
                            $this->_helper->redirector('list-services', 'admin', 'services', array('category' => $category));
                        } catch (Exception $e) {
                            $this->_helper->flashMessenger->setNamespace('errorMessages')
                                ->addMessage($this->view->translate($e));
                            $this->_helper->redirector('list-services', 'admin', 'services', array('category' => $category));
                        }
                    }
                }

                $this->view->assign('form', $form);
                $this->view->assign('images', $images);
                $this->view->assign('module_id', $this->_moduleId);
                $this->view->assign('action_id', 2);
                $this->view->assign('item', $item);
            } else {
                $this->_helper->flashMessenger->setNamespace('errorMessages')
                    ->addMessage($this->view->translate('servicesNotFound'));
                $this->_helper->redirector('list-services', 'admin', 'services', array('category' => $category));
            }
        } else {
            $this->_helper->flashMessenger->setNamespace('errorMessages')
                ->addMessage($this->view->translate('servicesNotFound'));
            $this->_helper->redirector('list-services', 'admin', 'services', array('category' => $category));
        }
    }

    public function removeServiceAction() {
        $id = $this->getParam('id');
        $category = $this->getParam('category', 0);
        $serviceImagesTable = new Services_Model_DbTable_ServicesImages();

        if ($id) {
            $service = $this->_itemsDbModel->find($id)->current();
            if ($service) {
                try {
                    $serviceImagesTable->deleteImagesByServiceAlias($service->alias);

                    $service->delete();

                    $this->_helper->flashMessenger->setNamespace('messages')
                        ->addMessage($this->view->translate('servicesDeleted'));
                    $this->_helper->redirector('list-services', 'admin', 'services', array('category' => $category));
                } catch (Exception $e) {
                    $this->_helper->flashMessenger->setNamespace('errorMessages')
                        ->addMessage($this->view->translate($e));
                    $this->_helper->redirector('list-services', 'admin', 'services', array('category' => $category));
                }
            }

        }

        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage($this->view->translate('servicesNotFound'));
        $this->_helper->redirector('list-services', 'admin', 'services', array('category' => $category));

    }

    public function deleteServiceImageAction() {
        $this->_helper->layout()->disableLayout();

        $imageId = $this->_request->getParam('imageId');
        $serviceImagesTable = new Services_Model_DbTable_ServicesImages();
        $image = $serviceImagesTable->find($imageId)->current();

        if (!$image) {
            echo json_encode(array('error' => 'error'));
            exit;
        }

        $image->delete();
        echo json_encode(array('success' => 'success'));
        exit;
    }
}