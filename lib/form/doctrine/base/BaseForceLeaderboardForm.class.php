<?php

/**
 * ForceLeaderboard form base class.
 *
 * @method ForceLeaderboard getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseForceLeaderboardForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tournament_id' => new sfWidgetFormInputHidden(),
      'timestamp'     => new sfWidgetFormInputHidden(),
      'rank'          => new sfWidgetFormInputHidden(),
      'user_id'       => new sfWidgetFormInputText(),
      'user_name'     => new sfWidgetFormInputText(),
      'power'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'tournament_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('tournament_id')), 'empty_value' => $this->getObject()->get('tournament_id'), 'required' => false)),
      'timestamp'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('timestamp')), 'empty_value' => $this->getObject()->get('timestamp'), 'required' => false)),
      'rank'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('rank')), 'empty_value' => $this->getObject()->get('rank'), 'required' => false)),
      'user_id'       => new sfValidatorInteger(array('required' => false)),
      'user_name'     => new sfValidatorPass(array('required' => false)),
      'power'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('force_leaderboard[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ForceLeaderboard';
  }

}
