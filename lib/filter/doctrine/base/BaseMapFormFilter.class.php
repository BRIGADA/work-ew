<?php

/**
 * Map filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMapFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'sector'              => new sfWidgetFormFilterInput(),
      'width'               => new sfWidgetFormFilterInput(),
      'height'              => new sfWidgetFormFilterInput(),
      'chunk_size'          => new sfWidgetFormFilterInput(),
      'outpost_levels'      => new sfWidgetFormFilterInput(),
      'upgrade_costs'       => new sfWidgetFormFilterInput(),
      'max_territory_limit' => new sfWidgetFormFilterInput(),
      'type'                => new sfWidgetFormFilterInput(),
      'active'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'maximum_node_level'  => new sfWidgetFormFilterInput(),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'sector'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'width'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'height'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'chunk_size'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'outpost_levels'      => new sfValidatorPass(array('required' => false)),
      'upgrade_costs'       => new sfValidatorPass(array('required' => false)),
      'max_territory_limit' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'                => new sfValidatorPass(array('required' => false)),
      'active'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'maximum_node_level'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('map_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Map';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'sector'              => 'Number',
      'width'               => 'Number',
      'height'              => 'Number',
      'chunk_size'          => 'Number',
      'outpost_levels'      => 'Text',
      'upgrade_costs'       => 'Text',
      'max_territory_limit' => 'Number',
      'type'                => 'Text',
      'active'              => 'Boolean',
      'maximum_node_level'  => 'Number',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
    );
  }
}
