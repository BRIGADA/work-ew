<?php

/**
 * Player form base class.
 *
 * @method Player getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePlayerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputText(),
      'level'       => new sfWidgetFormInputText(),
      'mainbase_id' => new sfWidgetFormInputText(),
      'colonies'    => new sfWidgetFormInputText(),
      'alliance_id' => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormInputText(),
      'login_at'    => new sfWidgetFormInputText(),
      'xp'          => new sfWidgetFormInputText(),
      'sp'          => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'        => new sfValidatorPass(array('required' => false)),
      'level'       => new sfValidatorInteger(array('required' => false)),
      'mainbase_id' => new sfValidatorInteger(array('required' => false)),
      'colonies'    => new sfValidatorPass(array('required' => false)),
      'alliance_id' => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorPass(array('required' => false)),
      'login_at'    => new sfValidatorPass(array('required' => false)),
      'xp'          => new sfValidatorInteger(array('required' => false)),
      'sp'          => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('player[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Player';
  }

}
