<?php

/**
 * Token
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    edgeworld
 * @subpackage model
 * @author     BRIGADA
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Token extends BaseToken
{
  public function getStatNames() {
    $result = array();
    
    foreach ($this->levels as $l) {
      foreach($l->stats as $k => $v) {
        if($v && !in_array($k, $result)) {
          $result[] = $k;          
        }
      }
    }
    return $result;
  }
  
  public function getStatSerie($stat, $default = NULL) {
    $result = array();
    
    foreach($this->levels as $level) {
      $result[] = isset($level->stats[$stat]) ? $level->stats[$stat] : $default;
    }
    
    return $result;
  }
}
