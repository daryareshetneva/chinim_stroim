<?php

class ItRocks_Form_Decorator_AdminImageView extends Zend_Form_Decorator_Abstract {

    protected $_format = '
            <div class="control-group">
                <label class="control-label">%s:</label>
                <div class="controls">
                    <img src="%s" class="preview"/>
                </div>
            </div>
	    ';
    protected $_options = array(
	'imageUrl', 'imageAlternate', 'removeUrl', 'removeTranslate' );

    public function render( $content ) {
	$element = $this->getElement();

	$view = $element->getView();
	if ( null === $view ) {
	    return $content;
	}

	$placement = $this->getPlacement();
	$separator = $this->getSeparator();

	$markup = sprintf( $this->_format, $view->translate( $this->getOption( 'imageTitle' ) ), $view->baseUrl() . DIRECTORY_SEPARATOR . $this->getOption( 'imageUrl' ) );
	switch ( $placement ) {
	    case 'PREPEND':
		$content = $markup . $separator . $content;
		break;

	    case 'APPEND':
	    default:
		$content = $content . $separator . $markup;
	}

	return $content;
    }

}
