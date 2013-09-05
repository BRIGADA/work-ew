<?php

/**
 * GeneralLevel filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGeneralLevelFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'requirements' => new sfWidgetFormFilterInput(),
      'stats'        => new sfWidgetFormFilterInput(),
      'skills'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'requirements' => new sfValidatorPass(array('required' => false)),
      'stats'        => new sfValidatorPass(array('required' => false)),
      'skills'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('general_level_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GeneralLevel';
  }

  public function getFields()
  {
    return array(
      'general_id'   => 'Number',
      'level'        => 'Number',
      'requirements' => 'Text',
      'stats'        => 'Text',
      'skills'       => 'Text',
    );
  }
}
