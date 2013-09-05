<?php

/**
 * ForceLeaderboard filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseForceLeaderboardFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'       => new sfWidgetFormFilterInput(),
      'user_name'     => new sfWidgetFormFilterInput(),
      'power'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_name'     => new sfValidatorPass(array('required' => false)),
      'power'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('force_leaderboard_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ForceLeaderboard';
  }

  public function getFields()
  {
    return array(
      'tournament_id' => 'Number',
      'timestamp'     => 'Number',
      'rank'          => 'Number',
      'user_id'       => 'Number',
      'user_name'     => 'Text',
      'power'         => 'Number',
    );
  }
}
