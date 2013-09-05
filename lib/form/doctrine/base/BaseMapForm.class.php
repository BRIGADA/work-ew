<?php

/**
 * Map form base class.
 *
 * @method Map getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseMapForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'sector'              => new sfWidgetFormInputText(),
      'width'               => new sfWidgetFormInputText(),
      'height'              => new sfWidgetFormInputText(),
      'chunk_size'          => new sfWidgetFormInputText(),
      'outpost_levels'      => new sfWidgetFormInputText(),
      'upgrade_costs'       => new sfWidgetFormInputText(),
      'max_territory_limit' => new sfWidgetFormInputText(),
      'type'                => new sfWidgetFormInputText(),
      'active'              => new sfWidgetFormInputCheckbox(),
      'maximum_node_level'  => new sfWidgetFormInputText(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'sector'              => new sfValidatorInteger(array('required' => false)),
      'width'               => new sfValidatorInteger(array('required' => false)),
      'height'              => new sfValidatorInteger(array('required' => false)),
      'chunk_size'          => new sfValidatorInteger(array('required' => false)),
      'outpost_levels'      => new sfValidatorPass(array('required' => false)),
      'upgrade_costs'       => new sfValidatorPass(array('required' => false)),
      'max_territory_limit' => new sfValidatorInteger(array('required' => false)),
      'type'                => new sfValidatorPass(array('required' => false)),
      'active'              => new sfValidatorBoolean(array('required' => false)),
      'maximum_node_level'  => new sfValidatorInteger(array('required' => false)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('map[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Map';
  }

}
