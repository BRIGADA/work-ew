<?php

/**
 * CampaignStageUnit form base class.
 *
 * @method CampaignStageUnit getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCampaignStageUnitForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'stage_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('stage'), 'add_empty' => false)),
      'type'     => new sfWidgetFormInputText(),
      'quantity' => new sfWidgetFormInputText(),
      'x'        => new sfWidgetFormInputText(),
      'y'        => new sfWidgetFormInputText(),
      'time'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'stage_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('stage'))),
      'type'     => new sfValidatorPass(array('required' => false)),
      'quantity' => new sfValidatorInteger(array('required' => false)),
      'x'        => new sfValidatorInteger(array('required' => false)),
      'y'        => new sfValidatorInteger(array('required' => false)),
      'time'     => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('campaign_stage_unit[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CampaignStageUnit';
  }

}
