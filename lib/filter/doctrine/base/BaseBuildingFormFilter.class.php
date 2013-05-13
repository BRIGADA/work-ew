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
      'type'   => new sfWidgetFormFilterInput(),
      'width'  => new sfWidgetFormFilterInput(),
      'height' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'type'   => new sfValidatorPass(array('required' => false)),
      'width'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'height' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
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
      'width'  => 'Number',
      'height' => 'Number',
    );
  }
}
