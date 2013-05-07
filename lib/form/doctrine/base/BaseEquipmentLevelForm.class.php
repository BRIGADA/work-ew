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
      'equipment_id'    => new sfWidgetFormInputHidden(),
      'level'           => new sfWidgetFormInputHidden(),
      'tier'            => new sfWidgetFormInputText(),
      'time'            => new sfWidgetFormInputText(),
      'upgrade_chance'  => new sfWidgetFormInputText(),
      'require_g'       => new sfWidgetFormInputText(),
      'require_e'       => new sfWidgetFormInputText(),
      'require_u'       => new sfWidgetFormInputText(),
      'require_c'       => new sfWidgetFormInputText(),
      'require_s'       => new sfWidgetFormInputText(),
      'stat_hp'         => new sfWidgetFormInputText(),
      'stat_range'      => new sfWidgetFormInputText(),
      'stat_rate'       => new sfWidgetFormInputText(),
      'stat_damage'     => new sfWidgetFormInputText(),
      'stat_targets'    => new sfWidgetFormInputText(),
      'stat_splash'     => new sfWidgetFormInputText(),
      'stat_concussion' => new sfWidgetFormInputCheckbox(),
      'stat_defense'    => new sfWidgetFormInputText(),
      'tags'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'equipment_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('equipment_id')), 'empty_value' => $this->getObject()->get('equipment_id'), 'required' => false)),
      'level'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('level')), 'empty_value' => $this->getObject()->get('level'), 'required' => false)),
      'tier'            => new sfValidatorInteger(array('required' => false)),
      'time'            => new sfValidatorInteger(array('required' => false)),
      'upgrade_chance'  => new sfValidatorInteger(array('required' => false)),
      'require_g'       => new sfValidatorInteger(array('required' => false)),
      'require_e'       => new sfValidatorInteger(array('required' => false)),
      'require_u'       => new sfValidatorInteger(array('required' => false)),
      'require_c'       => new sfValidatorInteger(array('required' => false)),
      'require_s'       => new sfValidatorInteger(array('required' => false)),
      'stat_hp'         => new sfValidatorInteger(array('required' => false)),
      'stat_range'      => new sfValidatorInteger(array('required' => false)),
      'stat_rate'       => new sfValidatorInteger(array('required' => false)),
      'stat_damage'     => new sfValidatorInteger(array('required' => false)),
      'stat_targets'    => new sfValidatorInteger(array('required' => false)),
      'stat_splash'     => new sfValidatorInteger(array('required' => false)),
      'stat_concussion' => new sfValidatorBoolean(array('required' => false)),
      'stat_defense'    => new sfValidatorNumber(array('required' => false)),
      'tags'            => new sfValidatorPass(array('required' => false)),
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
