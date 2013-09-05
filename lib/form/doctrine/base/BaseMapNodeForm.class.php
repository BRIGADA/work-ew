<?php

/**
 * MapNode form base class.
 *
 * @method MapNode getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseMapNodeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'map_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('map'), 'add_empty' => false)),
      'x'             => new sfWidgetFormInputText(),
      'y'             => new sfWidgetFormInputText(),
      'owner'         => new sfWidgetFormInputText(),
      'owner_id'      => new sfWidgetFormInputText(),
      'collection'    => new sfWidgetFormInputText(),
      'collection_id' => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'map_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('map'))),
      'x'             => new sfValidatorInteger(),
      'y'             => new sfValidatorInteger(),
      'owner'         => new sfValidatorPass(array('required' => false)),
      'owner_id'      => new sfValidatorInteger(array('required' => false)),
      'collection'    => new sfValidatorPass(array('required' => false)),
      'collection_id' => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('map_node[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MapNode';
  }

}
