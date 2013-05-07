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
      'tier'            => new sfWidgetFormFilterInput(),
      'time'            => new sfWidgetFormFilterInput(),
      'upgrade_chance'  => new sfWidgetFormFilterInput(),
      'require_g'       => new sfWidgetFormFilterInput(),
      'require_e'       => new sfWidgetFormFilterInput(),
      'require_u'       => new sfWidgetFormFilterInput(),
      'require_c'       => new sfWidgetFormFilterInput(),
      'require_s'       => new sfWidgetFormFilterInput(),
      'stat_hp'         => new sfWidgetFormFilterInput(),
      'stat_range'      => new sfWidgetFormFilterInput(),
      'stat_rate'       => new sfWidgetFormFilterInput(),
      'stat_damage'     => new sfWidgetFormFilterInput(),
      'stat_targets'    => new sfWidgetFormFilterInput(),
      'stat_splash'     => new sfWidgetFormFilterInput(),
      'stat_concussion' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'stat_defense'    => new sfWidgetFormFilterInput(),
      'tags'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'tier'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'time'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'upgrade_chance'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'require_g'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'require_e'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'require_u'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'require_c'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'require_s'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stat_hp'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stat_range'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stat_rate'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stat_damage'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stat_targets'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stat_splash'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stat_concussion' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'stat_defense'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'tags'            => new sfValidatorPass(array('required' => false)),
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
      'equipment_id'    => 'Number',
      'level'           => 'Number',
      'tier'            => 'Number',
      'time'            => 'Number',
      'upgrade_chance'  => 'Number',
      'require_g'       => 'Number',
      'require_e'       => 'Number',
      'require_u'       => 'Number',
      'require_c'       => 'Number',
      'require_s'       => 'Number',
      'stat_hp'         => 'Number',
      'stat_range'      => 'Number',
      'stat_rate'       => 'Number',
      'stat_damage'     => 'Number',
      'stat_targets'    => 'Number',
      'stat_splash'     => 'Number',
      'stat_concussion' => 'Boolean',
      'stat_defense'    => 'Number',
      'tags'            => 'Text',
    );
  }
}
