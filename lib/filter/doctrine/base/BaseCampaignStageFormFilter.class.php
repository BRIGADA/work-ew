<?php

/**
 * CampaignStage filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCampaignStageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'campaign_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('campaign'), 'add_empty' => true)),
      'name'                => new sfWidgetFormFilterInput(),
      'attacker_level'      => new sfWidgetFormFilterInput(),
      'attacker_boost'      => new sfWidgetFormFilterInput(),
      'unit_level'          => new sfWidgetFormFilterInput(),
      'baseline_xp'         => new sfWidgetFormFilterInput(),
      'player_unlock_level' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'campaign_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('campaign'), 'column' => 'id')),
      'name'                => new sfValidatorPass(array('required' => false)),
      'attacker_level'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'attacker_boost'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'unit_level'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'baseline_xp'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'player_unlock_level' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('campaign_stage_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CampaignStage';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'campaign_id'         => 'ForeignKey',
      'name'                => 'Text',
      'attacker_level'      => 'Number',
      'attacker_boost'      => 'Number',
      'unit_level'          => 'Number',
      'baseline_xp'         => 'Number',
      'player_unlock_level' => 'Number',
    );
  }
}
