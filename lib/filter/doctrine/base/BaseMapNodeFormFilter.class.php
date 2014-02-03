<?php

/**
 * MapNode filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMapNodeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'map_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('map'), 'add_empty' => true)),
      'x'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'y'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'level'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'owner'         => new sfWidgetFormFilterInput(),
      'owner_id'      => new sfWidgetFormFilterInput(),
      'collection'    => new sfWidgetFormFilterInput(),
      'collection_id' => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'map_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('map'), 'column' => 'id')),
      'x'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'y'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'owner'         => new sfValidatorPass(array('required' => false)),
      'owner_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'collection'    => new sfValidatorPass(array('required' => false)),
      'collection_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('map_node_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MapNode';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'map_id'        => 'ForeignKey',
      'x'             => 'Number',
      'y'             => 'Number',
      'level'         => 'Number',
      'owner'         => 'Text',
      'owner_id'      => 'Number',
      'collection'    => 'Text',
      'collection_id' => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
