<?php

/**
 * DefenseLevel filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseDefenseLevelFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'time'         => new sfWidgetFormFilterInput(),
      'requirements' => new sfWidgetFormFilterInput(),
      'stats'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'time'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'requirements' => new sfValidatorPass(array('required' => false)),
      'stats'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('defense_level_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DefenseLevel';
  }

  public function getFields()
  {
    return array(
      'defense_id'   => 'Number',
      'level'        => 'Number',
      'time'         => 'Number',
      'requirements' => 'Text',
      'stats'        => 'Text',
    );
  }
}
