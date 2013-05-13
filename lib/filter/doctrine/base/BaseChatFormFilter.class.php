<?php

/**
 * Chat filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseChatFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'room'        => new sfWidgetFormFilterInput(),
      'message'     => new sfWidgetFormFilterInput(),
      'sender_id'   => new sfWidgetFormFilterInput(),
      'sender'      => new sfWidgetFormFilterInput(),
      'alliance_id' => new sfWidgetFormFilterInput(),
      'alliance'    => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'room'        => new sfValidatorPass(array('required' => false)),
      'message'     => new sfValidatorPass(array('required' => false)),
      'sender_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sender'      => new sfValidatorPass(array('required' => false)),
      'alliance_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'alliance'    => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('chat_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Chat';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'room'        => 'Text',
      'message'     => 'Text',
      'sender_id'   => 'Number',
      'sender'      => 'Text',
      'alliance_id' => 'Number',
      'alliance'    => 'Text',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
