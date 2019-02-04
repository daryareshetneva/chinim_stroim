<?php

class ItRocks_Form_Decorator_AdminButtonForGroup extends Zend_Form_Decorator_Abstract {

    protected $_format = '<input class="btn %s" type="%s" name="%s" value="%s" />';

    public function render( $content ) {
        $element = $this->getElement();
        $view = $element->getView();
        if ( null === $view ) {
            return $content;
        }

        $label = $element->getLabel();
        
        $name = $element->getName();
        $class = $view->escape($element->getAttrib('class'));
        $type = $view->escape($element->getAttrib('type'));

        $markup = sprintf(
                $this->_format,
                $class,
                $type,
                $name,
                $view->translate( $label )
        );
        return $markup;
    }

}
