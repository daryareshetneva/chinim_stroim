<?php

class Portfolio_AdminController extends Zend_Controller_Action {

    protected $_catalog = null;
    protected $_portfolio = null;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $action = $this->_request->getActionName();

        if (in_array($action, array('delete', 'edit', 'show-services'))) {
            $portfolioCatalogsTable = new Portfolio_Model_DbTable_PortfolioCatalogs();
            $this->_catalog = $portfolioCatalogsTable->find($this->_request->getParam('id'))->current();

            if (!$this->_catalog)
                $this->_catalogNotFound();
        }

        if (in_array($action, array('edit-portfolio', 'delete-portfolio',
            'view-comments', 'delete-comment', 'show-comment'))) {
            $portfolioTable = new Portfolio_Model_DbTable_Portfolio();
            $this->_portfolio = $portfolioTable->find($this->_request->getParam('portfolioId'))->current();

            if (!$this->_portfolio)
                $this->_portfolioNotFound();
        }

    }

    public function indexAction() {
        $page = $this->_request->getParam('page', 1);

        $portfolioCatalogsTable = new Portfolio_Model_DbTable_PortfolioCatalogs();
        $catalogs = $portfolioCatalogsTable->getAll();

        $paginator = Zend_Paginator::factory($catalogs);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);

        $this->view->assign('paginator', $paginator);
    }

    public function addAction() {
        $portfolioCatalogsTable = new Portfolio_Model_DbTable_PortfolioCatalogs();
        $catalog = $portfolioCatalogsTable->fetchNew();
        $form = new Portfolio_Form_PortfolioCatalog($catalog);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($this->_request->getPost())) {
                if (empty($formData["alias"])) {
                    $transl = new Model_Transliterate();
                    $formData["alias"] = $transl->transliterate($formData["title"]);
                }

                if (empty($formData["metaTitle"])) {
                    $formData["metaTitle"] = $formData["title"];
                }

                if (empty($formData["metaDescription"])) {
                    $formData["metaDescription"] = $formData["title"];
                }
                $catalog->setFromArray($formData);
                $catalog->save();

                $this->_helper->flashMessenger->setNamespace('messages')->addMessage($this->view->translate('portfolioCategoryCreated'));
                $this->_helper->redirector('index', 'admin', 'portfolio');
            }
        }

        $this->view->assign('form', $form);
    }

    public function editAction() {
        $form = new Portfolio_Form_PortfolioCatalog($this->_catalog);

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $formData = $this->_request->getPost();
                if (empty($formData["alias"])) {
                    $transl = new Model_Transliterate();
                    $formData["alias"] = $transl->transliterate($formData["title"]);
                }

                if (empty($formData["metaTitle"])) {
                    $formData["metaTitle"] = $formData["title"];
                }

                if (empty($formData["metaDescription"])) {
                    $formData["metaDescription"] = $formData["title"];
                }
                $this->_catalog->setFromArray($formData);
                $this->_catalog->save();

                $this->_helper->flashMessenger->setNamespace('messages')->addMessage($this->view->translate('portfolioCategoryEdited'));
                $this->_helper->redirector('index', 'admin', 'portfolio');
            }
        }

        $this->view->assign('form', $form);
    }

    public function deleteAction() {
        $portfolioTable = new Portfolio_Model_DbTable_Portfolio();
        $portfolios = $portfolioTable->getByCatalogId($this->_catalog->id);

        foreach ($portfolios as $portfolio) {
            $dbPortfolio = $portfolioTable->find($portfolio['id'])->current();
            $dbPortfolio->delete();
        }

        $this->_catalog->delete();

        $this->_helper->flashMessenger->setNamespace('messages')->addMessage($this->view->translate('portfolioCategoryDeleted'));
        $this->_helper->redirector('index', 'admin', 'portfolio');
    }

    public function showServicesAction() {
        $page = $this->_request->getParam('page', 1);

        $portfolioTable = new Portfolio_Model_Portfolio();
        $portfolio = $portfolioTable->getPortfoliosWithCommentsByCatalogId($this->_catalog->id);

        $paginator = Zend_Paginator::factory($portfolio);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);

        $this->view->assign('paginator', $paginator);
        $this->view->assign('portfolioCatalog', $this->_catalog);
    }

    public function addPortfolioAction() {
        $catalogId = $this->_request->getParam('id');
        $portfolioTable = new Portfolio_Model_DbTable_Portfolio();
        $portfolio = $portfolioTable->fetchNew();
        $portfolio->portfolioCatalogId = $catalogId;

        $form = new Portfolio_Form_Portfolio($portfolio);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            echo $formData;
            try {
                if ($form->isValid($formData)) {
                    if (empty($formData["alias"])){
                        $transl = new Model_Transliterate();
                        $formData["alias"] = $transl->transliterate($formData["title"]);
                    }

                    if (empty($formData["metaTitle"])){
                        $formData["metaTitle"] = $formData["title"];
                    }

                    if (empty($formData["metaDescription"])){
                        $formData["metaDescription"] = $formData["title"];
                    }

                    $formData['date'] = date('Y-m-d H:i:s', strtotime($formData['date']));

                    $portfolio->setFromArray($formData);
                    $portfolio->save();

                    if (!empty($_FILES['image']['name'][0])) {
                        $fileUpload = new Model_FileUpload();
                        $portfolioImagesTable = new Portfolio_Model_DbTable_PortfolioImages;

                        for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
                            $filename = $fileUpload->uploadPortfolioPhoto($_FILES['image']['tmp_name'][$i], $_FILES['image']['name'][$i]);
                            $portfolioImagesTable->add($filename, $portfolio->id);
                        }
                    }

                    $this->_helper->flashMessenger->setNamespace('messages')->addMessage($this->view->translate('portfolioCreated'));
                    $this->_helper->redirector('show-services', 'admin', 'portfolio', array('id' => $formData['portfolioCatalogId']));
                }
            } catch (Exception $e) {
                $this->view->assign('error', $e->getMessage());
            }
        }

        $this->view->assign('form', $form);
    }

    public function editPortfolioAction() {
        $portfolioImages = new Portfolio_Model_DbTable_PortfolioImages;
        $images = $portfolioImages->getImagesByPortfolioId($this->_portfolio->id);
        $form = new Portfolio_Form_Portfolio($this->_portfolio);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            try {
                if ($form->isValid($formData)) {
                    if (empty($formData["alias"])){
                        $transl = new Model_Transliterate();
                        $formData["alias"] = $transl->transliterate($formData["title"]);
                    }

                    if (empty($formData["metaTitle"])){
                        $formData["metaTitle"] = $formData["title"];
                    }

                    if (empty($formData["metaDescription"])){
                        $formData["metaDescription"] = $formData["title"];
                    }

                    $formData['date'] = date('Y-m-d H:i:s', strtotime($formData['date']));

                    $this->_portfolio->setFromArray($formData);
                    $this->_portfolio->save();

                    if (!empty($_FILES['image']['name'][0])) {
                        $fileUpload = new Model_FileUpload();

                        for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
                            $filename = $fileUpload->uploadPortfolioPhoto($_FILES['image']['tmp_name'][$i], $_FILES['image']['name'][$i]);
                            $portfolioImages->add($filename, $this->_portfolio->id);
                        }
                    }

                    $this->_helper->flashMessenger->setNamespace('messages')->addMessage($this->view->translate('portfolioEdited'));
                    $this->_helper->redirector('show-services', 'admin', 'portfolio', array('id' => $formData['portfolioCatalogId']));
                }
            } catch (Exception $e) {
                $this->view->assign('error', $e->getMessage());
            }
        }

        $this->view->assign('form', $form);
        $this->view->assign('images', $images);
    }

    public function deletePortfolioAction() {
        $catalogId = $this->_portfolio->portfolioCatalogId;
        $this->_portfolio->delete();

        $this->_helper->flashMessenger->setNamespace('messages')->addMessage($this->view->translate('portfolioDeleted'));
        $this->_helper->redirector('show-services', 'admin', 'portfolio', array('id' => $catalogId));

        $this->_helper->layout()->disableLayout();
    }

    public function deletePortfolioImageAction() {
        $this->_helper->layout()->disableLayout();

        $imageId = $this->_request->getParam('imageId');
        $portfolioImagesTable = new Portfolio_Model_DbTable_PortfolioImages;
        $image = $portfolioImagesTable->find($imageId)->current();
        if (!$image) {
            echo json_encode(array('error' => 'error'));
            exit;
        }

        $image->delete();
        echo json_encode(array('success' => 'success'));
        exit;
    }

    public function viewCommentsAction() {
        $workCommentsTable = new Portfolio_Model_DbTable_PortfolioComments();

        $this->view->assign('comments', $workCommentsTable->getByPortfolioId($this->_portfolio->id));
        $this->view->assign('portfolio', $this->_portfolio);
    }

    public function showCommentAction() {
        $workCommentsTable = new Portfolio_Model_DbTable_PortfolioComments();
        $comment = $workCommentsTable->find($this->_request->getParam('commentId'))->current();

        if (!$comment)
            $this->_commentNotFound();

        $form = new Portfolio_Form_AdminComment($comment);

        if ($this->_request->getPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $formData['new'] = 0;
                $comment->setFromArray($formData);
                $comment->save();
                $this->_helper->redirector('view-comments', 'admin', 'portfolio',
                    array(
                        'id' => $this->_portfolio->portfolioCatalogId,
                        'portfolioId' => $this->_portfolio->id
                    )
                );
            }
        }

        $this->view->assign('form', $form);
    }

    public function deleteCommentAction() {
        $workCommentsTable = new Portfolio_Model_DbTable_PortfolioComments();
        $comment = $workCommentsTable->find($this->_request->getParam('commentId'))->current();

        if (!$comment)
            $this->_commentNotFound();

        $comment->delete();
        $this->_helper->redirector('view-comments', 'admin', 'portfolio',
            array(
                'id' => $this->_portfolio->portfolioCatalogId,
                'portfolioId' => $this->_portfolio->id
            )
        );
    }

    protected function _commentNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage($this->view->translate('commentNotFound'));
        $this->_helper->redirector('view-comments', 'admin', 'portfolio', array(
                'id' => $this->_portfolio->portfolioCatalogId,
                'portfolioId' => $this->_portfolio->id
            )
        );
    }

    protected function _catalogNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage($this->view->translate('portfolioCatalogNotFound'));
        $this->_helper->redirector('index', 'admin', 'portfolio');
    }

    protected function _portfolioNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
            ->addMessage($this->view->translate('portfolioNotFound'));
        $this->_helper->redirector('index', 'admin', 'portfolio');
    }

}
