<?php

/**
 * Item form base class.
 *
 * @method Item getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseItemForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'type'               => new sfWidgetFormInputText(),
      'permanent'          => new sfWidgetFormInputCheckbox(),
      'boost_amount'       => new sfWidgetFormInputText(),
      'boost_percentage'   => new sfWidgetFormInputText(),
      'boost_type'         => new sfWidgetFormInputText(),
      'resource_amount'    => new sfWidgetFormInputText(),
      'resource_type'      => new sfWidgetFormInputText(),
      'image_name'         => new sfWidgetFormInputText(),
      'success_multiplier' => new sfWidgetFormInputText(),
      'sp'                 => new sfWidgetFormInputText(),
      'required_for_use'   => new sfWidgetFormInputText(),
      'contents'           => new sfWidgetFormInputText(),
      'tags'               => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'type'               => new sfValidatorPass(),
      'permanent'          => new sfValidatorBoolean(array('required' => false)),
      'boost_amount'       => new sfValidatorInteger(array('required' => false)),
      'boost_percentage'   => new sfValidatorInteger(array('required' => false)),
      'boost_type'         => new sfValidatorPass(array('required' => false)),
      'resource_amount'    => new sfValidatorInteger(array('required' => false)),
      'resource_type'      => new sfValidatorPass(array('required' => false)),
      'image_name'         => new sfValidatorPass(array('required' => false)),
      'success_multiplier' => new sfValidatorInteger(array('required' => false)),
      'sp'                 => new sfValidatorInteger(array('required' => false)),
      'required_for_use'   => new sfValidatorPass(array('required' => false)),
      'contents'           => new sfValidatorPass(array('required' => false)),
      'tags'               => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Item';
  }

}
