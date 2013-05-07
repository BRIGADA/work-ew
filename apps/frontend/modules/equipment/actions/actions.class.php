<?php

/**
 * equipment actions.
 *
 * @package    edgeworld
 * @subpackage equipment
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class equipmentActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$data = file_get_contents(sfConfig::get('sf_upload_dir').'/api-player-equipment.json');
  	$this->results = json_decode($data)->response->equipment;
  	$this->types = array();

  	foreach($this->results as $equipment) {
  		if(!in_array($equipment->type, $this->types)) {
  			$this->types[] = $equipment->type;
  		}
  	}
  	
  	$this->bonus = Doctrine::getTable('Equipment')
  		->createQuery('e INDEXBY e.type')
  		->leftJoin('e.levels l')
  		->select('e.type, (max(l.stat_hp) > 0) hp, (max(l.stat_range) > 0) range, (max(l.stat_rate) > 0) rate, (max(l.stat_damage) > 0) damage, (max(l.stat_targets) > 0) targets, (max(l.stat_splash) > 0) splash, (max(l.stat_concussion) > 0) concussion, (max(l.stat_defense) > 0) defense')
  		->whereIn('e.type', $this->types)
  		->groupBy('e.id')
  		->fetchArray();
  	
  	sort($this->types);
  }
  
  function executeInfo(sfWebRequest $request)
  {
  	$this->type = $request->getParameter('type');
  	$this->forward404Unless($this->type);
  	
  	$equipment = Doctrine::getTable('Equipment')->findOneBy('type', $this->type);
  	$this->forward404Unless($equipment);
  	
  	$this->levels = Doctrine::getTable('EquipmentLevel')
  		->createQuery()
  		->where('equipment_id = ?', $equipment->id)
  		->orderBy('level')
  		->execute();
  	
  }
  
  function executeUpgrade(sfWebRequest $request)
  {  	
  	$id = $request->getParameter('id');
  	$this->forward404Unless($id);
  	 
  	$this->getUser()->setAttribute('host', 'sector15.c1.galactic.wonderhill.com', 'client');
  	
  	$query = array();
  	$query['testCount'] = $this->getUser()->getAttribute('testCount', 1, 'client');
  	$query['basis_id'] = $this->getUser()->getAttribute('basis_id', '649094', 'client');
  	$query['_session_id'] = $this->getUser()->getAttribute('_session_id', 'null', 'client');
  	$query['reactor'] = $this->getUser()->getAttribute('reactor', '8694f154769b1fe1a6c174209b7df65c6418e31c', 'client');
  	$query['_method'] = $this->getUser()->getAttribute('_method', 'post', 'client');
  	$query['meltdown'] = $this->getUser()->getAttribute('meltdown', '411931f7071e2e801486560d4883ca56e76ebee8', 'client');
  	$query['user_id'] = $this->getUser()->getAttribute('user_id', '608208', 'client');
  	 
  	$r = $this->getUser()->RPOST(sprintf('/api/player/equipment/%s/instant_upgrade', $id), $query);
  	return $this->renderText(json_encode($r));
// testCount=1&basis%5Fid=649094&%5Fsession%5Fid=null&reactor=8694f154769b1fe1a6c174209b7df65c6418e31c&%5Fmethod=post&meltdown=411931f7071e2e801486560d4883ca56e76ebee8&user%5Fid=608208  	
// testCount=1&basis%5Fid=649094&%5Fsession%5Fid=null&reactor=8694f154769b1fe1a6c174209b7df65c6418e31c&%5Fmethod=post&meltdown=411931f7071e2e801486560d4883ca56e76ebee8&user%5Fid=608208  	
  	
  }
}
