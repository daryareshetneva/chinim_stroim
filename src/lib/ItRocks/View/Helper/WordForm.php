<?php

class ItRocks_View_Helper_WordForm extends Zend_View_Helper_Abstract {

	protected $_value;
	protected $_word;
	protected $_wordForms;

	const FormSimple = 0;
	const FormSecond = 1;
	const FormThird = 2;

	public function __construct() {
		$this->initWords();
	}

	/**
	 * Use wordForm()->getForm( $value, $word )
	 * 
	 * @return \Borsch_View_Helper_WordForm
	 */
	public function wordForm() {
		return $this;
	}

	public function getForm($value, $word) {
		try {
			$this->setWord( $word );
			$this->setValue( $value );
		} catch ( Exception $ex ) {
			return 'Error! ' . $ex->getMessage();
		}
		return $this->getWord();
	}

	private function setValue($value) {
		if ( is_string( $value ) ) {
			$this->_value = intval( $value );
		} else if ( is_int( $value ) ) {
			$this->_value = $value;
		} else {
			throw new Exception( "Value not support" );
		}
	}

	private function setWord($word) {
		if ( in_array( $word, array_keys( $this->_wordForms ) ) ) {
			$this->_word = $word;
		} else {
			throw new Exception( "Word not support" );
		}
	}

	private function getWord() {
		// super russian formula
		if ( $this->_value % 10 == 1 &&
				$this->_value % 100 != 11 ) {
			return $this->_wordForms[$this->_word][self::FormSimple];
		} else if ( $this->_value % 10 >= 2 && $this->_value % 10 <= 4 &&
				( $this->_value % 100 < 10 || $this->_value % 100 >= 20 )
		) {
			return $this->_wordForms[$this->_word][self::FormSecond];
		} else {
			return $this->_wordForms[$this->_word][self::FormThird];
		}
	}

	private function initWords() {
		$this->_wordForms = array(
			'день' => array(
				self::FormSimple => 'день',
				self::FormSecond => 'дня',
				self::FormThird => 'дней'
			),
			'товар' => array(
				self::FormSimple => 'товар',
				self::FormSecond => 'товара',
				self::FormThird => 'товаров'
			),
			'деталь' => array(
				self::FormSimple => 'деталь',
				self::FormSecond => 'детали',
				self::FormThird => 'деталей'
			)
		);
	}

}
