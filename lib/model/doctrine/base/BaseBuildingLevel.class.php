<?php

/**
 * BaseBuildingLevel
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $building_id
 * @property integer $level
 * @property integer $time
 * @property array $requirements
 * @property array $stats
 * @property Building $building
 * 
 * @method integer       getBuildingId()   Returns the current record's "building_id" value
 * @method integer       getLevel()        Returns the current record's "level" value
 * @method integer       getTime()         Returns the current record's "time" value
 * @method array         getRequirements() Returns the current record's "requirements" value
 * @method array         getStats()        Returns the current record's "stats" value
 * @method Building      getBuilding()     Returns the current record's "building" value
 * @method BuildingLevel setBuildingId()   Sets the current record's "building_id" value
 * @method BuildingLevel setLevel()        Sets the current record's "level" value
 * @method BuildingLevel setTime()         Sets the current record's "time" value
 * @method BuildingLevel setRequirements() Sets the current record's "requirements" value
 * @method BuildingLevel setStats()        Sets the current record's "stats" value
 * @method BuildingLevel setBuilding()     Sets the current record's "building" value
 * 
 * @package    edgeworld
 * @subpackage model
 * @author     BRIGADA
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBuildingLevel extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('building_levels');
        $this->hasColumn('building_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('level', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('time', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('requirements', 'array', null, array(
             'type' => 'array',
             ));
        $this->hasColumn('stats', 'array', null, array(
             'type' => 'array',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Building as building', array(
             'local' => 'building_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE',
             'onUpdate' => 'CASCADE'));
    }
}