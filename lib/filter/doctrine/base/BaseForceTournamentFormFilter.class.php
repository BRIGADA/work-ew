<?php

/**
 * ForceTournament filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseForceTournamentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'dates'               => new sfWidgetFormFilterInput(),
      'sector'              => new sfWidgetFormFilterInput(),
      'type'                => new sfWidgetFormFilterInput(),
      'end_at'              => new sfWidgetFormFilterInput(),
      'daily_prizing'       => new sfWidgetFormFilterInput(),
      'bout_prizing'        => new sfWidgetFormFilterInput(),
      'challenge_prizing'   => new sfWidgetFormFilterInput(),
      'active_calculations' => new sfWidgetFormFilterInput(),
      'value_adjustments'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'dates'               => new sfValidatorPass(array('required' => false)),
      'sector'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'                => new sfValidatorPass(array('required' => false)),
      'end_at'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'daily_prizing'       => new sfValidatorPass(array('required' => false)),
      'bout_prizing'        => new sfValidatorPass(array('required' => false)),
      'challenge_prizing'   => new sfValidatorPass(array('required' => false)),
      'active_calculations' => new sfValidatorPass(array('required' => false)),
      'value_adjustments'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('force_tournament_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ForceTournament';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'dates'               => 'Text',
      'sector'              => 'Number',
      'type'                => 'Text',
      'end_at'              => 'Number',
      'daily_prizing'       => 'Text',
      'bout_prizing'        => 'Text',
      'challenge_prizing'   => 'Text',
      'active_calculations' => 'Text',
      'value_adjustments'   => 'Text',
    );
  }
}
