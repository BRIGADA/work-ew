<?php

/**
 * ForceTournament form base class.
 *
 * @method ForceTournament getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseForceTournamentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'dates'               => new sfWidgetFormInputText(),
      'sector'              => new sfWidgetFormInputText(),
      'type'                => new sfWidgetFormInputText(),
      'end_at'              => new sfWidgetFormInputText(),
      'daily_prizing'       => new sfWidgetFormInputText(),
      'bout_prizing'        => new sfWidgetFormInputText(),
      'challenge_prizing'   => new sfWidgetFormInputText(),
      'active_calculations' => new sfWidgetFormInputText(),
      'value_adjusments'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'dates'               => new sfValidatorPass(array('required' => false)),
      'sector'              => new sfValidatorInteger(array('required' => false)),
      'type'                => new sfValidatorPass(array('required' => false)),
      'end_at'              => new sfValidatorInteger(array('required' => false)),
      'daily_prizing'       => new sfValidatorPass(array('required' => false)),
      'bout_prizing'        => new sfValidatorPass(array('required' => false)),
      'challenge_prizing'   => new sfValidatorPass(array('required' => false)),
      'active_calculations' => new sfValidatorPass(array('required' => false)),
      'value_adjusments'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('force_tournament[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ForceTournament';
  }

}
