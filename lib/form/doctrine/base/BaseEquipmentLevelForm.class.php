<?php

/**
 * EquipmentLevel form base class.
 *
 * @method EquipmentLevel getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEquipmentLevelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'equipment_id'   => new sfWidgetFormInputHidden(),
      'level'          => new sfWidgetFormInputHidden(),
      'tier'           => new sfWidgetFormInputText(),
      'time'           => new sfWidgetFormInputText(),
      'upgrade_chance' => new sfWidgetFormInputText(),
      'requirements'   => new sfWidgetFormInputText(),
      'stats'          => new sfWidgetFormInputText(),
      'tags'           => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'equipment_id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('equipment_id')), 'empty_value' => $this->getObject()->get('equipment_id'), 'required' => false)),
      'level'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('level')), 'empty_value' => $this->getObject()->get('level'), 'required' => false)),
      'tier'           => new sfValidatorInteger(array('required' => false)),
      'time'           => new sfValidatorInteger(array('required' => false)),
      'upgrade_chance' => new sfValidatorInteger(array('required' => false)),
      'requirements'   => new sfValidatorPass(array('required' => false)),
      'stats'          => new sfValidatorPass(array('required' => false)),
      'tags'           => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('equipment_level[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EquipmentLevel';
  }

}
