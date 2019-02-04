<?php

class ItRocks_Form_Decorator_AdminButtonGroup extends Zend_Form_Decorator_Abstract {

    protected $_format = '<div class="form-actions">%s</div>';

    public function render( $content ) {
        $markup = sprintf(
                $this->_format,
                $content
        );
        return $markup;
    }

}
