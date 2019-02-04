<?php

class ItRocks_Form_Decorator_FrontInlineText extends Zend_Form_Decorator_Abstract {

    protected $_format = '
        <div class="form-group %s">
	    <input type="%s" name="%s" value="%s" placeholder="%s" %s class="form-control %s" />
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

        $type = $element->getAttrib( 'type' );
        $name = $element->getFullyQualifiedName();
        $label = $element->getLabel();
        $placeholder = $element->getAttrib( 'placeholder' );
        $class = $element->getAttrib( 'class' );
        $value = $view->escape( $element->getValue() );
        $errors = $element->getMessages();

        //flags
        $readonly = $element->getAttrib( 'readonly' ) ? 'readonly' : '';
        $required = $element->getAttrib( 'isRequired' ) ? 'required' : '';

        //valid
        $pattern = $element->getAttrib( 'pattern' ) ?
                'pattern="' . $element->getAttrib( 'pattern' ) . '"' : '';

        $flags = $readonly . ' ' . $required . ' ' . $pattern;

        $markup = sprintf(
                $this->_format, (count( $errors ) > 0) ? 'error' : '', $type, $name, $value,
                ($placeholder) ? $view->translate( $placeholder ) : '', $flags, $class
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
