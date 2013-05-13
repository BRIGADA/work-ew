<?php

/**
 * GarrisonBoost form base class.
 *
 * @method GarrisonBoost getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGarrisonBoostForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'    => new sfWidgetFormInputHidden(),
      'type'  => new sfWidgetFormInputText(),
      'value' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'type'  => new sfValidatorPass(array('required' => false)),
      'value' => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('garrison_boost[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GarrisonBoost';
  }

}
