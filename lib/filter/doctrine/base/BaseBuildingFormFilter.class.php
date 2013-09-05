<?php

/**
 * Building filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseBuildingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'size_x' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'size_y' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'type'   => new sfValidatorPass(array('required' => false)),
      'size_x' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'size_y' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('building_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Building';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'type'   => 'Text',
      'size_x' => 'Number',
      'size_y' => 'Number',
    );
  }
}
