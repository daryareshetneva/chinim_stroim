<?php

class ItRocks_Form_Decorator_AdminTextarea extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="control-group %s">
            <label class="control-label">%s:</label>
            <div class="controls">
                <textarea name="%s" id="%s" class="%s" rows="%s">%s</textarea>
                %s
            </div>
        </div>
        ';
    protected $_errorFormat = "
        <span class=\"help-block\">%s</span>\n\t
        ";

    public function render( $content ) {
        $element = $this->getElement();
        $view = $element->getView();
        if ( null === $view ) {
            return $content;
        }


        $name = $element->getFullyQualifiedName();
        $label = $element->getLabel();
        $class = $element->getAttrib( 'class' );
        $id = $element->getId();
        $rows = $element->getAttrib( 'rows' );
        $readonly = $element->getAttrib( 'readonly' );
        $value = $view->escape( $element->getValue() );
        $errors = $element->getMessages();

        $markup = sprintf(
                $this->_format, (count( $errors ) > 0) ? 'error' : '',
                $view->translate( $label ), $name, $id, $class, $rows, $value,
                (empty( $errors )) ? '' : $this->_formatErrors( $errors )
        );
        return $markup;
    }

    protected function _formatErrors( $errors ) {
        $errorString = '';
        foreach ( $errors as $error ) {
            $errorString .= sprintf( $this->_errorFormat, $error );
        }
        return $errorString;
    }

}
