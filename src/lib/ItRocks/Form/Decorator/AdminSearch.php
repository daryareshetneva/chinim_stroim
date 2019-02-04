<?php

class ItRocks_Form_Decorator_AdminSearch extends Zend_Form_Decorator_Abstract
{

    protected $_format = '
            <div class="autocomplete-append">
                <input type="text" name="%s" value="%s" class="ui-autocomplete-input %s" id="autocomplete" autocomplete="off" onfocus = "if(!this._haschanged){this.value=\'\'};this._haschanged=true"/>
                <input type="submit" class="btn btn-info" value="Искать">
            </div>
        ';  
    
    public function render($content)
    {  
        $element = $this->getElement();
        $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
        $name = $view->escape($element->getFullyQualifiedName());
        $class = $view->escape($element->getAttrib('class'));
        $value = $view->escape($element->getValue());
        
        $markup = sprintf($this->_format, $name, $value, $class);
        
        return $markup;
    }

}
