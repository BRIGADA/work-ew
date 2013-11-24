<?php

/**
 * TokenLevel form base class.
 *
 * @method TokenLevel getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTokenLevelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'token_id' => new sfWidgetFormInputHidden(),
      'level'    => new sfWidgetFormInputHidden(),
      'stats'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'token_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('token_id')), 'empty_value' => $this->getObject()->get('token_id'), 'required' => false)),
      'level'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('level')), 'empty_value' => $this->getObject()->get('level'), 'required' => false)),
      'stats'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('token_level[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TokenLevel';
  }

}
