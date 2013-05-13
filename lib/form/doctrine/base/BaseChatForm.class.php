<?php

/**
 * Chat form base class.
 *
 * @method Chat getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseChatForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'room'        => new sfWidgetFormInputText(),
      'message'     => new sfWidgetFormInputText(),
      'sender_id'   => new sfWidgetFormInputText(),
      'sender'      => new sfWidgetFormInputText(),
      'alliance_id' => new sfWidgetFormInputText(),
      'alliance'    => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'room'        => new sfValidatorPass(array('required' => false)),
      'message'     => new sfValidatorPass(array('required' => false)),
      'sender_id'   => new sfValidatorInteger(array('required' => false)),
      'sender'      => new sfValidatorPass(array('required' => false)),
      'alliance_id' => new sfValidatorInteger(array('required' => false)),
      'alliance'    => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('chat[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Chat';
  }

}
