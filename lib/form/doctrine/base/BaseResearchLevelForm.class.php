<?php

/**
 * ResearchLevel form base class.
 *
 * @method ResearchLevel getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseResearchLevelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'research_id'  => new sfWidgetFormInputHidden(),
      'level'        => new sfWidgetFormInputHidden(),
      'time'         => new sfWidgetFormInputText(),
      'requirements' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'research_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('research_id')), 'empty_value' => $this->getObject()->get('research_id'), 'required' => false)),
      'level'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('level')), 'empty_value' => $this->getObject()->get('level'), 'required' => false)),
      'time'         => new sfValidatorInteger(array('required' => false)),
      'requirements' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('research_level[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ResearchLevel';
  }

}
