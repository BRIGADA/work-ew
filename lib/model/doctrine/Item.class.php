<?php

/**
 * Item
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    edgeworld
 * @subpackage model
 * @author     BRIGADA
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Item extends BaseItem
{
  public function getImage() {
    return empty($this->image_name) ? (strtolower($this->type).'.png') : $this->image_name;
  }
}
