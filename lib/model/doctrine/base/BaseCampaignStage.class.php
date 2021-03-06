<?php

/**
 * BaseCampaignStage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $campaign_id
 * @property text $name
 * @property integer $attacker_level
 * @property float $attacker_boost
 * @property integer $unit_level
 * @property integer $baseline_xp
 * @property integer $player_unlock_level
 * @property Doctrine_Collection $units
 * @property Campaign $campaign
 * 
 * @method integer             getId()                  Returns the current record's "id" value
 * @method integer             getCampaignId()          Returns the current record's "campaign_id" value
 * @method text                getName()                Returns the current record's "name" value
 * @method integer             getAttackerLevel()       Returns the current record's "attacker_level" value
 * @method float               getAttackerBoost()       Returns the current record's "attacker_boost" value
 * @method integer             getUnitLevel()           Returns the current record's "unit_level" value
 * @method integer             getBaselineXp()          Returns the current record's "baseline_xp" value
 * @method integer             getPlayerUnlockLevel()   Returns the current record's "player_unlock_level" value
 * @method Doctrine_Collection getUnits()               Returns the current record's "units" collection
 * @method Campaign            getCampaign()            Returns the current record's "campaign" value
 * @method CampaignStage       setId()                  Sets the current record's "id" value
 * @method CampaignStage       setCampaignId()          Sets the current record's "campaign_id" value
 * @method CampaignStage       setName()                Sets the current record's "name" value
 * @method CampaignStage       setAttackerLevel()       Sets the current record's "attacker_level" value
 * @method CampaignStage       setAttackerBoost()       Sets the current record's "attacker_boost" value
 * @method CampaignStage       setUnitLevel()           Sets the current record's "unit_level" value
 * @method CampaignStage       setBaselineXp()          Sets the current record's "baseline_xp" value
 * @method CampaignStage       setPlayerUnlockLevel()   Sets the current record's "player_unlock_level" value
 * @method CampaignStage       setUnits()               Sets the current record's "units" collection
 * @method CampaignStage       setCampaign()            Sets the current record's "campaign" value
 * 
 * @package    edgeworld
 * @subpackage model
 * @author     BRIGADA
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCampaignStage extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('campaigns_stages');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('campaign_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('name', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('attacker_level', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('attacker_boost', 'float', null, array(
             'type' => 'float',
             ));
        $this->hasColumn('unit_level', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('baseline_xp', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('player_unlock_level', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('CampaignStageUnit as units', array(
             'local' => 'id',
             'foreign' => 'stage_id',
             'onDelete' => 'CASCADE',
             'onUpdate' => 'CASCADE',
             'orderBy' => 'time, id'));

        $this->hasOne('Campaign as campaign', array(
             'local' => 'campaign_id',
             'foreign' => 'id'));
    }
}