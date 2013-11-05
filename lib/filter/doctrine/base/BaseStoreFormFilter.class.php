<?php

/**
 * Store filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseStoreFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'item_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('item'), 'add_empty' => true)),
      'price'          => new sfWidgetFormFilterInput(),
      'featured_until' => new sfWidgetFormFilterInput(),
      'sale'           => new sfWidgetFormFilterInput(),
      'purchasable'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'usable'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'priority_id'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'item_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('item'), 'column' => 'id')),
      'price'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'featured_until' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sale'           => new sfValidatorPass(array('required' => false)),
      'purchasable'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'usable'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'priority_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('store_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Store';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'item_id'        => 'ForeignKey',
      'price'          => 'Number',
      'featured_until' => 'Number',
      'sale'           => 'Text',
      'purchasable'    => 'Boolean',
      'usable'         => 'Boolean',
      'priority_id'    => 'Number',
    );
  }
}
