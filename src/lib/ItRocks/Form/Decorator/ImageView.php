<?php

class ItRocks_Form_Decorator_ImageView extends Zend_Form_Decorator_Abstract
{
	protected
		$_options = array('imageUrl', 'imageAlternate', 'removeUrl', 'removeTranslate');

	public function render($content) {
		$element = $this->getElement();

		$view = $element->getView();
		if (null === $view) {
			return $content;
		}

		$placement = $this->getPlacement();
		$separator = $this->getSeparator();

        $markup  = sprintf('<fieldset>            
            <img src="%s" alt="%s" class="preview"/>
            </fieldset>',
			$view->baseUrl() . DIRECTORY_SEPARATOR . $this->getOption('imageUrl'),
			$this->getOption('imageAlternate'),
			$this->getOption('removeUrl'),
			$this->getOption('removeTranslate'),
			$this->getOption('removeTranslate'));
		switch ($placement) {
			case 'PREPEND':
				$content = $markup . $separator .  $content;
				break;

			case 'APPEND':
			default:
				$content = $content . $separator . $markup;
		}

		return $content;
	}
}
