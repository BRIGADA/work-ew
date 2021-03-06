<?php

/**
 * BaseCraftingRecipe
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property text $name
 * @property array $inputs
 * @property array $outputs
 * 
 * @method text           getName()    Returns the current record's "name" value
 * @method array          getInputs()  Returns the current record's "inputs" value
 * @method array          getOutputs() Returns the current record's "outputs" value
 * @method CraftingRecipe setName()    Sets the current record's "name" value
 * @method CraftingRecipe setInputs()  Sets the current record's "inputs" value
 * @method CraftingRecipe setOutputs() Sets the current record's "outputs" value
 * 
 * @package    edgeworld
 * @subpackage model
 * @author     BRIGADA
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCraftingRecipe extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('crafting_recipe');
        $this->hasColumn('name', 'text', null, array(
             'type' => 'text',
             'notnull' => true,
             ));
        $this->hasColumn('inputs', 'array', null, array(
             'type' => 'array',
             ));
        $this->hasColumn('outputs', 'array', null, array(
             'type' => 'array',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}