<?php
class  Zend_View_Helper_ViewTreeCategories extends Zend_View_Helper_Abstract
{
    private $_alias = '';
    private $_list ='';
    protected $_listTemplate = '
    <li class="%s">
        <a href="%s%s">
            %s
        </a>
    </li>
    ';

    public function viewTreeCategories($tree, $alias){
        $this->_alias = $alias;
        $this->_buildTree($tree);
        return $this->_list;
    }

    private function _buildTree($tree)
    {
        foreach ($tree as $key => $item) {
            $this->_list .= sprintf($this->_listTemplate, ($item['alias'] == $this->_alias) ? 'active' : '',$this->_url(), $item['alias'], $item['title']);
            if (isset($item['categories'])) {
                $this->_list .= '<ul>';
                $this->_buildTree($item['categories']);
                $this->_list .= '</ul>';
            }
        }
    }
    private function _url($module = 'shop'){
        $url = new Zend_View_Helper_BaseUrl();
        return $url->baseUrl($module.'/');
    }
}