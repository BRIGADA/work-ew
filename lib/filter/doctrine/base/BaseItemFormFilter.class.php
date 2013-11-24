<?php

/**
 * Item filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseItemFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'permanent'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'boost_amount'       => new sfWidgetFormFilterInput(),
      'boost_percentage'   => new sfWidgetFormFilterInput(),
      'boost_type'         => new sfWidgetFormFilterInput(),
      'resource_amount'    => new sfWidgetFormFilterInput(),
      'resource_type'      => new sfWidgetFormFilterInput(),
      'image_name'         => new sfWidgetFormFilterInput(),
      'success_multiplier' => new sfWidgetFormFilterInput(),
      'sp'                 => new sfWidgetFormFilterInput(),
      'required_for_use'   => new sfWidgetFormFilterInput(),
      'contents'           => new sfWidgetFormFilterInput(),
      'tags'               => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'type'               => new sfValidatorPass(array('required' => false)),
      'permanent'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'boost_amount'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'boost_percentage'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'boost_type'         => new sfValidatorPass(array('required' => false)),
      'resource_amount'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'resource_type'      => new sfValidatorPass(array('required' => false)),
      'image_name'         => new sfValidatorPass(array('required' => false)),
      'success_multiplier' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sp'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'required_for_use'   => new sfValidatorPass(array('required' => false)),
      'contents'           => new sfValidatorPass(array('required' => false)),
      'tags'               => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Item';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'type'               => 'Text',
      'permanent'          => 'Boolean',
      'boost_amount'       => 'Number',
      'boost_percentage'   => 'Number',
      'boost_type'         => 'Text',
      'resource_amount'    => 'Number',
      'resource_type'      => 'Text',
      'image_name'         => 'Text',
      'success_multiplier' => 'Number',
      'sp'                 => 'Number',
      'required_for_use'   => 'Text',
      'contents'           => 'Text',
      'tags'               => 'Text',
    );
  }
}
