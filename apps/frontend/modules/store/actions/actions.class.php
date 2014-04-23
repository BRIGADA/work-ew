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
        $r = $this->getUser()->RGET('/api/player');
        $this->forwardUnless($r, 'common', 'index');

        $data = json_decode($r, true);
        
        $this->items = $data['response']['items'];
        
        $this->bases = array();
        $this->bases[] = array('id' => $data['response']['base']['id'], 'name' => $data['response']['base']['name']);
        foreach ($data['response']['colonies'] as $colony) {
            $this->bases[] = array('id' => $colony['id'], 'name' => $colony['name']);
        }
        
//        $this->manifest_items = ItemTable::getInstance()->createQuery('INDEXBY type')->fetchArray();
//        $this->manifest_store = StoreTable::getInstance()->createQuery()->fetchArray();
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
