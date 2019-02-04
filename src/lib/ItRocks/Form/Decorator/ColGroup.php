<?php

class ItRocks_Form_Decorator_ColGroup extends Zend_Form_Decorator_Abstract {

    protected $_format = '<div class="%s">%s</div>';
    protected $_class = 'col-md-12';

    public function render( $content ) {
        $markup = sprintf(
                $this->_format,
		$this->_class,
                $content
        );
        return $markup;
    }

    public function setClass( $class ) {
	$this->_class = $class;
    }
}