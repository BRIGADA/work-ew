<?php

/**
 * MapRevard filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMapRevardFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'value'   => new sfWidgetFormFilterInput(),
      'rewards' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'value'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rewards' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('map_revard_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MapRevard';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'value'   => 'Number',
      'rewards' => 'Text',
    );
  }
}
