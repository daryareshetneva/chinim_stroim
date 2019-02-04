<?php
class News_IndexController extends ItRocks_Controller_Action {

    public function init() {
        $tagsModel = new Model_Tags();
        $tags = $tagsModel->getTagsByMCA(
            $this->_request->getModuleName(),
            $this->_request->getControllerName(),
            'index'
        );

        $this->_addMeta($tags['metaTitle'], $tags['metaDescription']);
        $this->view->assign('title', $tags['title']);
    }

    public function indexAction() {
        $page = $this->_request->getParam('page', 1);
        $paginator = $this->_getNewsPaginator($page);
        $pagesAmount = $paginator->getPages()->pageCount;


        $currentPageNumber = $paginator->getCurrentPageNumber();
        $requestPageNumber = (int)$this->_request->getParam('page');
        if (($requestPageNumber <= 0) || ($currentPageNumber < $requestPageNumber) || (!is_int($requestPageNumber))) {
            $this->error404();
        }

        $this->view->paginator = $paginator;
        $this->view->assign('pagesAmount', $pagesAmount);
    }
    
    public function showAction() {

        $itemAlias  = $this->_request->getParam('alias');
        $newsTable = new News_Model_DbTable_Table();
        $news = $newsTable->getByAlias($itemAlias);

        if ($news){
            $this->view->headTitle()->prepend($news->metaTitle);
            $this->view->headMeta()->setName('description',$news->metaDescription);
            $this->view->headMeta()->setName('keywords', $news->metaKeywords);
            $this->view->assign('news', $news);
        } else {

            $this->getResponse()->setHttpResponseCode(404);
            $this->view->setBasePath(APPLICATION_PATH . '/views/');
            $this->_helper->viewRenderer->renderBySpec('error', ['module' => 'default', 'controller' => 'error']);
            $this->_addMeta('404! Страница не существует');
        }
    }

    protected function _newsNotFound() {
        $this->_helper->flashMessenger->setNamespace('errorMessages')
                                      ->addMessage($this->view->translate('notFound'));
        
        $this->_helper->redirector('index');
    }


    /**
     * TODO Сделать рефакторинг тегов
     */
    public function ajaxGetNewsAction(){
        $this->_helper->layout()->disableLayout();
        $page = $this->_request->getParam('page', 1);

        $this->view->paginator = $this->_getNewsPaginator($page);
    }

    public function newsWidgetAction() {
        $newsTale = new News_Model_DbTable_Table();
        $items = $newsTale->getLastNews(3);

        $this->view->assign('items', $items);
    }

    private function _getNewsPaginator($page) {
        $news = new News_Model_DbTable_Table();
        $allNews = $news->getAllNews();

        $paginator = Zend_Paginator::factory($allNews);
        $paginator->setItemCountPerPage(3);
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }
}
