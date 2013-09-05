<?php

/**
 * CampaignStage form base class.
 *
 * @method CampaignStage getObject() Returns the current form's model object
 *
 * @package    edgeworld
 * @subpackage form
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCampaignStageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'campaign_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('campaign'), 'add_empty' => false)),
      'name'                => new sfWidgetFormInputText(),
      'attacker_level'      => new sfWidgetFormInputText(),
      'attacker_boost'      => new sfWidgetFormInputText(),
      'unit_level'          => new sfWidgetFormInputText(),
      'baseline_xp'         => new sfWidgetFormInputText(),
      'player_unlock_level' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'campaign_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('campaign'))),
      'name'                => new sfValidatorPass(array('required' => false)),
      'attacker_level'      => new sfValidatorInteger(array('required' => false)),
      'attacker_boost'      => new sfValidatorNumber(array('required' => false)),
      'unit_level'          => new sfValidatorInteger(array('required' => false)),
      'baseline_xp'         => new sfValidatorInteger(array('required' => false)),
      'player_unlock_level' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('campaign_stage[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CampaignStage';
  }

}
