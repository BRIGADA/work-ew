<?php

/**
 * MapRevard form base class.
 *
 * @method MapRevard getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseMapRevardForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'value'   => new sfWidgetFormInputText(),
      'rewards' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'value'   => new sfValidatorInteger(array('required' => false)),
      'rewards' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('map_revard[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MapRevard';
  }

}
