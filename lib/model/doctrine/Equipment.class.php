<?php

/**
 * Equipment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    edgeworld
 * @subpackage model
 * @author     BRIGADA
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Equipment extends BaseEquipment {

  public function getStatsTypes() {
    $result = array();
    foreach($this->levels as $level) {
      foreach($level->stats as $stat => $value) {
        if(!in_array($stat, $result) && ($value !== 'false') && $value) {
          $result[] = $stat;
        }
      }
    }
    return $result;
  }

}
