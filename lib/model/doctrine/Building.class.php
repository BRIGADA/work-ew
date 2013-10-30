<?php

/**
 * Building
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    edgeworld
 * @subpackage model
 * @author     BRIGADA
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Building extends BaseBuilding
{
    public function getStat($stat)
    {
        $result = array();
        foreach($this->levels as $l) {
            $result[] = (isset($l->stats) && isset($l->stats[$stat])) ? $l->stats[$stat] : null;
        }
        return $result;
    }
}
