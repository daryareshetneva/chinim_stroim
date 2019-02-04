<?php
class  Zend_View_Helper_TagsView extends Zend_View_Helper_Abstract
{
    protected $_template = '
    <aside class="sidebar">
        <ul class="nav nav-list mb-xlg">
            %s
        </ul>
    </aside>
    ';

    protected $_listTemplate = '
    <li>
        <a href="%s%u">
            %s
        </a>
    </li>
    ';


    public function tagsView($tags, $module = 'blog'){
        $list = '';

        foreach ($tags as $tag) {
            $list .= sprintf($this->_listTemplate, $this->url($module), $tag['id'], $tag['name'].' ('.$tag['count'].')');
        }
        return sprintf($this->_template, $list);
    }
    private function url($module = 'blog'){
        $url = new Zend_View_Helper_BaseUrl();
        return $url->baseUrl($module.'/showtag/');
    }
}