<?php

/**
 * BaseSkillLevel
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $skill_id
 * @property integer $level
 * @property array $requirements
 * @property array $stats
 * @property Skill $skill
 * 
 * @method integer    getSkillId()      Returns the current record's "skill_id" value
 * @method integer    getLevel()        Returns the current record's "level" value
 * @method array      getRequirements() Returns the current record's "requirements" value
 * @method array      getStats()        Returns the current record's "stats" value
 * @method Skill      getSkill()        Returns the current record's "skill" value
 * @method SkillLevel setSkillId()      Sets the current record's "skill_id" value
 * @method SkillLevel setLevel()        Sets the current record's "level" value
 * @method SkillLevel setRequirements() Sets the current record's "requirements" value
 * @method SkillLevel setStats()        Sets the current record's "stats" value
 * @method SkillLevel setSkill()        Sets the current record's "skill" value
 * 
 * @package    edgeworld
 * @subpackage model
 * @author     BRIGADA
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSkillLevel extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('skill_levels');
        $this->hasColumn('skill_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('level', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
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
        $this->hasOne('Skill as skill', array(
             'local' => 'skill_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE',
             'onUpdate' => 'CASCADE',
             'orderBy' => 'level'));
    }
}