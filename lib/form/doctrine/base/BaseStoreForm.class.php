<?php

/**
 * Store form base class.
 *
 * @method Store getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStoreForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'item_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('item'), 'add_empty' => false)),
      'price'          => new sfWidgetFormInputText(),
      'featured_until' => new sfWidgetFormInputText(),
      'sale'           => new sfWidgetFormInputText(),
      'purchasable'    => new sfWidgetFormInputCheckbox(),
      'usable'         => new sfWidgetFormInputCheckbox(),
      'priority_id'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'item_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('item'))),
      'price'          => new sfValidatorInteger(array('required' => false)),
      'featured_until' => new sfValidatorInteger(array('required' => false)),
      'sale'           => new sfValidatorPass(array('required' => false)),
      'purchasable'    => new sfValidatorBoolean(),
      'usable'         => new sfValidatorBoolean(),
      'priority_id'    => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('store[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Store';
  }

}
