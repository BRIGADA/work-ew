<?php

/**
 * BaseResearchLevel
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $research_id
 * @property integer $level
 * @property integer $time
 * @property array $requirements
 * @property Research $research
 * 
 * @method integer       getResearchId()   Returns the current record's "research_id" value
 * @method integer       getLevel()        Returns the current record's "level" value
 * @method integer       getTime()         Returns the current record's "time" value
 * @method array         getRequirements() Returns the current record's "requirements" value
 * @method Research      getResearch()     Returns the current record's "research" value
 * @method ResearchLevel setResearchId()   Sets the current record's "research_id" value
 * @method ResearchLevel setLevel()        Sets the current record's "level" value
 * @method ResearchLevel setTime()         Sets the current record's "time" value
 * @method ResearchLevel setRequirements() Sets the current record's "requirements" value
 * @method ResearchLevel setResearch()     Sets the current record's "research" value
 * 
 * @package    edgeworld
 * @subpackage model
 * @author     BRIGADA
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseResearchLevel extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('research_levels');
        $this->hasColumn('research_id', 'integer', null, array(
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
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Research as research', array(
             'local' => 'research_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE',
             'onUpdate' => 'CASCADE'));
    }
}