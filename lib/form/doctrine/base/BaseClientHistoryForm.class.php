<?php

/**
 * ClientHistory form base class.
 *
 * @method ClientHistory getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseClientHistoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'host'        => new sfWidgetFormInputText(),
      'meltdown'    => new sfWidgetFormInputText(),
      'reactor'     => new sfWidgetFormInputText(),
      'user_id'     => new sfWidgetFormInputText(),
      '_session_id' => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'host'        => new sfValidatorPass(array('required' => false)),
      'meltdown'    => new sfValidatorPass(array('required' => false)),
      'reactor'     => new sfValidatorPass(array('required' => false)),
      'user_id'     => new sfValidatorInteger(array('required' => false)),
      '_session_id' => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('client_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClientHistory';
  }

}
