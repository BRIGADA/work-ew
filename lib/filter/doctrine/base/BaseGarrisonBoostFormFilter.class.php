<?php

/**
 * GarrisonBoost filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGarrisonBoostFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'  => new sfWidgetFormFilterInput(),
      'value' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'type'  => new sfValidatorPass(array('required' => false)),
      'value' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('garrison_boost_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GarrisonBoost';
  }

  public function getFields()
  {
    return array(
      'id'    => 'Number',
      'type'  => 'Text',
      'value' => 'Number',
    );
  }
}
