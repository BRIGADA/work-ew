<?php

/**
 * DefenseLevel form base class.
 *
 * @method DefenseLevel getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDefenseLevelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'defense_id'   => new sfWidgetFormInputHidden(),
      'level'        => new sfWidgetFormInputHidden(),
      'time'         => new sfWidgetFormInputText(),
      'requirements' => new sfWidgetFormInputText(),
      'stats'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'defense_id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('defense_id')), 'empty_value' => $this->getObject()->get('defense_id'), 'required' => false)),
      'level'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('level')), 'empty_value' => $this->getObject()->get('level'), 'required' => false)),
      'time'         => new sfValidatorInteger(array('required' => false)),
      'requirements' => new sfValidatorPass(array('required' => false)),
      'stats'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('defense_level[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DefenseLevel';
  }

}
