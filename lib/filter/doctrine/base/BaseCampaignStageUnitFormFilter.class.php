<?php

/**
 * CampaignStageUnit filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCampaignStageUnitFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'stage_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('stage'), 'add_empty' => true)),
      'type'     => new sfWidgetFormFilterInput(),
      'quantity' => new sfWidgetFormFilterInput(),
      'x'        => new sfWidgetFormFilterInput(),
      'y'        => new sfWidgetFormFilterInput(),
      'time'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'stage_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('stage'), 'column' => 'id')),
      'type'     => new sfValidatorPass(array('required' => false)),
      'quantity' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'x'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'y'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'time'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('campaign_stage_unit_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CampaignStageUnit';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'stage_id' => 'ForeignKey',
      'type'     => 'Text',
      'quantity' => 'Number',
      'x'        => 'Number',
      'y'        => 'Number',
      'time'     => 'Number',
    );
  }
}
