<?php

/**
 * store actions.
 *
 * @package    edgeworld
 * @subpackage store
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class storeActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->result = Doctrine::getTable('Store')
            ->createQuery('s')
            ->leftJoin('s.item i')
            ->execute();
  }
  
}

//  POST /api/player/items/171674590
//  _method=delete
//  _session_id=3a4768ff9e88ecbbaaf64346918b40fd
//  basis_id=1997028
//  meltdown=4b13397f296c4b2d721048508ce71c74f6e646af
//  reactor=8694f154769b1fe1a6c174209b7df65c6418e31c
//  testCount=432
//  user_id=608208

//  POST /api/player/items/
//  _method=post
//  _session_id=3a4768ff9e88ecbbaaf64346918b40fd
//  basis_id=1997028
//  box_type=MysteryBoxQ
//  meltdown=06ca047465a7340a01845894d1620f3292f63fab
//  reactor=8694f154769b1fe1a6c174209b7df65c6418e31c
//  testCount=433
//  user_id=608208
