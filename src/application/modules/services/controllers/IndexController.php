<?php
class Services_IndexController extends ItRocks_Controller_Action {

    private $_itemsModel;
    private $_treeModel;
    protected $_moduleId = null;

    public function init() {
        $this->_treeModel = new Services_Model_DbTable_Tree();
        $this->_itemsModel = new Services_Model_DbTable_Items();

        $moduleTable = new Model_DbTable_Modules();
        $module = $moduleTable->getModuleIdByName('services');
        $this->_moduleId = $module['id'];

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
//        $categories = array();
//        $services = array();
//        $description = array();
//        $alias = $this->_request->getParam('alias', null);
        $id = $this->getParam('id', 0);

        $servicesCatrgoriesTable = new Services_Model_DbTable_Tree();
        $servicesItemsTable = new Services_Model_DbTable_Items();

        $categories = $servicesCatrgoriesTable->getAll();
        $activeCategory = $servicesCatrgoriesTable->getCategoryInformationById($id);
        $items = $servicesItemsTable->getByCategory($id);

        $this->view->assign('activeCategory', $activeCategory);
        $this->view->assign('categories', $categories);
        $this->view->assign('items', $items);

        //var_dump($activeCategory); exit;
//        if ($alias) {
//            if ($this->_treeModel->checkAlias($alias)) {
//                $this->error404();
//            } else {
//                $description = $this->_treeModel->getCategoryInformationByAlias($alias);
//                $categories = $this->_treeModel->getCategoriesByAlias($alias);
//                $services = $this->_itemsModel->getItemsByCategoryAlias($alias);
//                $this->_addMeta($description['metaTitle'], $description['metaDescription']);
//            }
//        } else {
//            $categories = $this->_treeModel->getCategoriesByParentId($start_id);
//        }

//        $this->view->assign('start_id', $start_id);
//        $this->view->assign('categories', $categories);
//        $this->view->assign('services', $services);
//        $this->view->assign('description', $description);
    }

    public function showAction() {
        $alias = $this->getParam('alias');
        $id = $this->getParam('id');
        if ($alias) {
            $item = $this->_itemsModel->getByAlias($alias);
        } elseif ($id) {
            $item = $this->_itemsModel->getById($id);
        } else {
            $item = null;
        }
//        $this->_addImageMetaTag($item['description'], '');

        $files = null;

        if ($item) {
            $this->_addMeta($item['meta_title'], $item['meta_description']);
            $uploaderModel = new Model_Uploader();
            $files = $uploaderModel->getFiles($this->_moduleId, 2, $item['id']);
        }

        $this->view->assign('files', $files);
        $this->view->assign('item', $item);
    }

    public function renderMenuAction() {
        $items = $this->_treeModel->getAll();
        $this->view->assign('treeRenderer', $this->_helper->nestedSetsRenderer);
        $this->view->assign('items', $items);
    }

    public function apiGetItemAction() {
        if ($this->_request->isPost()) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);

            $id = $this->_request->getPost('id');
            $model = new Services_Model_Tree();
            $items = $model->getItemsByCategory($id);
            echo json_encode($items);
        }
    }

    public function servicesHomeAction() {
        $servicesCatrgoriesTable = new Services_Model_DbTable_Tree();
        $categories = $servicesCatrgoriesTable->getLimit(3);

        $this->view->assign('multiplator', $categories);
    }

    public function servicesHomeItemAction() {
        $servicesItemsTable = new Services_Model_DbTable_Items();
        $alias = $this->_request->getParam('currentCategory');

        $items = $servicesItemsTable->getItemsByCategoryAlias($alias);

        $this->view->assign('multiplator', $items);
    }

    public function servicesExampleAction() {
        $serviceId = $this->getParam('serviceId');

        // текущая услуга
        $servicesTable = new Services_Model_DbTable_Items();
        $service = $servicesTable->getById($serviceId);
        $this->view->assign('service', $service);

        // картинки текущей услуги
        $servicesImagesTable = new Services_Model_DbTable_ServicesImages();
        $images = $servicesImagesTable->getImagesByServiceAlias($service['alias']);
        $this->view->assign('images', $images);

        // для ссылки на базовую страницу услуг (с выделенной дефолтной категорией)
        $servicesCatrgoriesTable = new Services_Model_DbTable_Tree();
        $defaultServicesCategory = $servicesCatrgoriesTable->getLimit(1);
        $this->view->assign('defaultServicesCategory', $defaultServicesCategory);

    }
}