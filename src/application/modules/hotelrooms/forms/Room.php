<?php

class HotelRooms_Form_Room extends Zend_Form {

    protected $_room = null;

    public function __construct(Zend_Db_Table_Row_Abstract $room) {
        $this->_room = $room;
        parent::__construct();
    }
    
    public function init() {
        $this->setDecorators(array('FormElements', 'Form'));
        $this->addAttribs( array( 'class' => 'form-horizontal' ) );

        $textareaDecorator  = new ItRocks_Form_Decorator_AdminTextarea;
        $textDecorator      = new ItRocks_Form_Decorator_AdminText;
        $submitDecorator    = new ItRocks_Form_Decorator_AdminSubmit;
        $fileDecorator      = new ItRocks_Form_Decorator_AdminFile();
        $checkboxDecorator  = new ItRocks_Form_Decorator_AdminCheckbox();

        $this->addElement($this->createElement('text', 'title', array(
            'required' => true,
            'label' => 'Название',
            'class' => 'span8',
            'value' => $this->_room->title,
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'pricePerDay', array(
            'required' => true,
            'label' => 'Цена за сутки',
            'class' => 'span8',
            'value' => $this->_room->pricePerDay,
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'pricePerHour', array(
            'required' => false,
            'label' => 'Цена за час',
            'class' => 'span8',
            'value' => $this->_room->pricePerHour,
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('file', 'photo', array(
            'required' => (!empty($this->_room->photo)) ? false : true,
            'label' => 'Основное изображение',
            'decorators' => array($fileDecorator),
            'validators' => array(
                array('Extension', false, 'jpg,jpeg,png,gif')
            )
        )));

        $imageHelper = new HotelRooms_Model_Images();
        $this->photo->addFilter($imageHelper);
        if ($this->_room->photo) {
            $this->photo->addDecorator(new ItRocks_Form_Decorator_ImageView(array(
                'imageUrl' => $imageHelper->url($this->_room->photo),
                'imageAlternate' => ''
            )));
        }

        $this->addElement($this->createElement('textarea', 'shortDescription', array(
            'required' => true,
            'label' => 'Краткое описание',
            'class' => 'span8',
            'value' => $this->_room->shortDescription,
            'decorators' => array($textareaDecorator)
        )));

        $this->addElement($this->createElement('textarea', 'description', array(
            'required' => true,
            'label' => 'Описание',
            'class' => 'span8',
            'value' => $this->_room->description,
            'decorators' => array($textareaDecorator)
        )));

        $this->addElement($this->createElement('text', 'personAmount', array(
            'required' => false,
            'label' => 'Вместимость(чел)',
            'class' => 'span8',
            'value' => $this->_room->personAmount,
            'decorators' => array($textDecorator)
        )));

        $this->addElement($this->createElement('text', 'bed', array(
            'required' => false,
            'label' => 'Кровать',
            'class' => 'span8',
            'value' => $this->_room->bed,
            'decorators' => array($checkboxDecorator)
        )));
        $this->addElement($this->createElement('text', 'fridge', array(
            'required' => false,
            'label' => 'Холодильник',
            'class' => 'span8',
            'value' => $this->_room->fridge,
            'decorators' => array($checkboxDecorator)
        )));
        $this->addElement($this->createElement('text', 'jacuzzi', array(
            'required' => false,
            'label' => 'Джакузи',
            'class' => 'span8',
            'value' => $this->_room->jacuzzi,
            'decorators' => array($checkboxDecorator)
        )));
        $this->addElement($this->createElement('text', 'satTv', array(
            'required' => false,
            'label' => 'Спутниковое ТВ',
            'class' => 'span8',
            'value' => $this->_room->satTv,
            'decorators' => array($checkboxDecorator)
        )));
        $this->addElement($this->createElement('text', 'electrofireplace', array(
            'required' => false,
            'label' => 'Электрокамин',
            'class' => 'span8',
            'value' => $this->_room->electrofireplace,
            'decorators' => array($checkboxDecorator)
        )));
        $this->addElement($this->createElement('text', 'sauna', array(
            'required' => false,
            'label' => 'Сауна',
            'class' => 'span8',
            'value' => $this->_room->sauna,
            'decorators' => array($checkboxDecorator)
        )));
        $this->addElement($this->createElement('text', 'wifi', array(
            'required' => false,
            'label' => 'Wi-Fi',
            'class' => 'span8',
            'value' => $this->_room->wifi,
            'decorators' => array($checkboxDecorator)
        )));
        $this->addElement($this->createElement('text', 'shower', array(
            'required' => false,
            'label' => 'Душ',
            'class' => 'span8',
            'value' => $this->_room->shower,
            'decorators' => array($checkboxDecorator)
        )));
        $this->addElement($this->createElement('text', 'fireplace', array(
            'required' => false,
            'label' => 'Камин',
            'class' => 'span8',
            'value' => $this->_room->fireplace,
            'decorators' => array($checkboxDecorator)
        )));
        $this->addElement($this->createElement('text', 'conditioner', array(
            'required' => false,
            'label' => 'Кондиционер',
            'class' => 'span8',
            'value' => $this->_room->conditioner,
            'decorators' => array($checkboxDecorator)
        )));
        $this->addElement($this->createElement('text', 'cupboard', array(
            'required' => false,
            'label' => 'Большой шкаф',
            'class' => 'span8',
            'value' => $this->_room->cupboard,
            'decorators' => array($checkboxDecorator)
        )));
        $this->addElement($this->createElement('text', 'minibar', array(
            'required' => false,
            'label' => 'Мини-бар',
            'class' => 'span8',
            'value' => $this->_room->minibar,
            'decorators' => array($checkboxDecorator)
        )));

        $this->addElement($this->createElement('submit', 'submit', array (
            'label' => (!empty($this->_room->title)) ? 'Редактировать' : 'Добавить',
            'decorators' => array($submitDecorator)
        )));
    }

    public function isValid($data){
        $ret = parent::isValid($data);

        $transliterateModel = new Model_Transliterate();
        $data['alias'] = $transliterateModel->transliterate($data['title']);


        if ($this->_room->alias != $data['alias'] || empty($this->_room->alias)) {
            $aliasValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'HotelRooms_Rooms',
                    'field' => 'alias'
                )
            );
            if (!$aliasValidator->isValid($data['alias'])) {
                $this->title->addError("Такое название уже существует. Придумайте другой вариант.");
                $ret = false;
            }
        }

        return $ret;

    }
}