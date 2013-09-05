<?php

/**
 * ClientHistory filter form base class.
 *
 * @package    edgeworld
 * @subpackage filter
 * @author     BRIGADA
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseClientHistoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'host'        => new sfWidgetFormFilterInput(),
      'meltdown'    => new sfWidgetFormFilterInput(),
      'reactor'     => new sfWidgetFormFilterInput(),
      'user_id'     => new sfWidgetFormFilterInput(),
      '_session_id' => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'host'        => new sfValidatorPass(array('required' => false)),
      'meltdown'    => new sfValidatorPass(array('required' => false)),
      'reactor'     => new sfValidatorPass(array('required' => false)),
      'user_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      '_session_id' => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('client_history_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClientHistory';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'host'        => 'Text',
      'meltdown'    => 'Text',
      'reactor'     => 'Text',
      'user_id'     => 'Number',
      '_session_id' => 'Text',
      'created_at'  => 'Date',
    );
  }
}
