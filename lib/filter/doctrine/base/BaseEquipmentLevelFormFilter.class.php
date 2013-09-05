<?php

/**
 * EquipmentLevel filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEquipmentLevelFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tier'           => new sfWidgetFormFilterInput(),
      'time'           => new sfWidgetFormFilterInput(),
      'upgrade_chance' => new sfWidgetFormFilterInput(),
      'requirements'   => new sfWidgetFormFilterInput(),
      'stats'          => new sfWidgetFormFilterInput(),
      'tags'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'tier'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'time'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'upgrade_chance' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'requirements'   => new sfValidatorPass(array('required' => false)),
      'stats'          => new sfValidatorPass(array('required' => false)),
      'tags'           => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('equipment_level_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EquipmentLevel';
  }

  public function getFields()
  {
    return array(
      'equipment_id'   => 'Number',
      'level'          => 'Number',
      'tier'           => 'Number',
      'time'           => 'Number',
      'upgrade_chance' => 'Number',
      'requirements'   => 'Text',
      'stats'          => 'Text',
      'tags'           => 'Text',
    );
  }
}
