<?php

/**
 * TokenLevel filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTokenLevelFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'stats'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'stats'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('token_level_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TokenLevel';
  }

  public function getFields()
  {
    return array(
      'token_id' => 'Number',
      'level'    => 'Number',
      'stats'    => 'Text',
    );
  }
}
