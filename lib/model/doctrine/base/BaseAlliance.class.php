<?php

/**
 * BaseAlliance
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property text $name
 * 
 * @method integer  getId()   Returns the current record's "id" value
 * @method text     getName() Returns the current record's "name" value
 * @method Alliance setId()   Sets the current record's "id" value
 * @method Alliance setName() Sets the current record's "name" value
 * 
 * @package    edgeworld
 * @subpackage model
 * @author     BRIGADA
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseAlliance extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('alliance');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('name', 'text', null, array(
             'type' => 'text',
             'notnull' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}