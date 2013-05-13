<?php

/**
 * SkillLevel form base class.
 *
 * @method SkillLevel getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSkillLevelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'skill_id'     => new sfWidgetFormInputHidden(),
      'level'        => new sfWidgetFormInputHidden(),
      'requirements' => new sfWidgetFormInputText(),
      'stats'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'skill_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('skill_id')), 'empty_value' => $this->getObject()->get('skill_id'), 'required' => false)),
      'level'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('level')), 'empty_value' => $this->getObject()->get('level'), 'required' => false)),
      'requirements' => new sfValidatorPass(array('required' => false)),
      'stats'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('skill_level[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SkillLevel';
  }

}
