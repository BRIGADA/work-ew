<?php

/**
 * BaseBuilding
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property text $type
 * @property integer $width
 * @property integer $height
 * @property Doctrine_Collection $levels
 * 
 * @method integer             getId()     Returns the current record's "id" value
 * @method text                getType()   Returns the current record's "type" value
 * @method integer             getWidth()  Returns the current record's "width" value
 * @method integer             getHeight() Returns the current record's "height" value
 * @method Doctrine_Collection getLevels() Returns the current record's "levels" collection
 * @method Building            setId()     Sets the current record's "id" value
 * @method Building            setType()   Sets the current record's "type" value
 * @method Building            setWidth()  Sets the current record's "width" value
 * @method Building            setHeight() Sets the current record's "height" value
 * @method Building            setLevels() Sets the current record's "levels" collection
 * 
 * @package    edgeworld
 * @subpackage model
 * @author     BRIGADA
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBuilding extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('buildings');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'autoincrement' => true,
             'primary' => true,
             ));
        $this->hasColumn('type', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('width', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('height', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('BuildingLevels as levels', array(
             'local' => 'id',
             'foreign' => 'building_id'));
    }
}