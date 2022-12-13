<?php
namespace User\Form;

use Laminas\I18n\Translator\Translator;

use Laminas\Form\Element;
use Laminas\Form\Form;

class UserForm extends Form
{
    public function __construct($cities)
    {

        parent::__construct('userForm');

        $this->setAttribute('method', 'post');
        
        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Name',
            ],
        ]);

        $this->add([
            'name' => 'surname',
            'type' => 'text',
            'options' => [
                'label' => 'Surname',
            ],
        ]);

        $this->add([
            'name' => 'furname',
            'type' => 'text',
            'options' => [
                'label' => 'Furname',
            ],
        ]);

        $this->add([
            'name' => 'birthday',
            'type' => 'date',
            'options' => [
                'label' => 'Birthday',
            ],
        ]);

        $translator = new Translator();

        $selectCityOptions = [];

        foreach ($cities as $city) {
            $selectCityOptions[$city->id] = $translator->translate($city->name, 'default', 'ru_RU');
        }

        $select = new Element\Select('id_birthplace');
        $select->setLabel('Birthplace');
        $select->setValueOptions($selectCityOptions);

        $this->add($select);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}