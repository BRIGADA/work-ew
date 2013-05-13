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
//  	$data = file_get_contents(sfConfig::get('sf_upload_dir').'/api-player-equipment.json');
		$r = $this->getUser()->RGET('/api/player/equipment');
		$this->forwardUnless($r, 'common', 'index');
		
		$data = json_decode($r);
		$this->forwardUnless($data, 'common', 'index');
		
  	$this->results = $data->response->equipment;
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
  	 
  	$query = array();
  	$query['basis_id'] = $this->getUser()->getAttribute('bases', array(), 'player')[0]['id'];
  	$query['_method'] = 'post';
  	 
  	$r = $this->getUser()->RPOST(sprintf('/api/player/equipment/%s/instant_upgrade', $id), $query);
  	
  	$this->forward404Unless($r);
  	
  	$result = json_decode($r); 
  	$this->forward404Unless($result);
  	 	
  	return $this->renderJSON($result->response->job->successful);

  	return $this->renderJSON(rand() & 1);
  	  
// testCount=1&basis%5Fid=649094&%5Fsession%5Fid=null&reactor=8694f154769b1fe1a6c174209b7df65c6418e31c&%5Fmethod=post&meltdown=411931f7071e2e801486560d4883ca56e76ebee8&user%5Fid=608208  	
// testCount=1&basis%5Fid=649094&%5Fsession%5Fid=null&reactor=8694f154769b1fe1a6c174209b7df65c6418e31c&%5Fmethod=post&meltdown=411931f7071e2e801486560d4883ca56e76ebee8&user%5Fid=608208  	
  	
  }
  
  function executeMultiDestroy(sfWebRequest $request)
  {
  	$ids = $request->getParameter('ids');
  	$this->forward404Unless($ids);
  	
  }
  
  function executeDestroy(sfWebRequest $request)
  {
  	$multi = $request->getParameter('multi', false);
  	
  	$query = array();
  	$query['_method'] = 'delete';
  	
  	if($multi)
  	{
  		$ids = $request->getParameter('ids');
  		$this->forward404Unless($ids);
  		
  		$query['ids'] = implode(',', $ids);
  		
  		$this->getLogger()->debug(var_export($query, true));
  		  		
  		$path = '/api/player/equipment/multidestroy';  		
  	}
  	else
  	{
  		$id = $request->getParameter('id');
  		$this->forward404Unless($id);
  		
  		$path = sprintf('/api/player/equipment/%s', $id);
  	}
  	
  	$r = $this->getUser()->RPOST($path, $query);
  	
  	$this->forward404Unless($r);
  	
  	$result = json_decode($r);
  	$this->forward404Unless($result);
  	  	
  	return $this->renderJSON($result->response->success);
  	
  	
		// /api/player/equipment/multidestroy
		// 
		// _method	delete
		// _session_id	null
		// ids	8659633,8659622,8659629
		// meltdown	d253c96da70d53649af4df29d2d2eac5855cade4
		// reactor	8694f154769b1fe1a6c174209b7df65c6418e31c
		// testCount	8
		// user_id	608208
  	
  	// /api/player/equipment/8659665
  	//
		// _method	delete
		// _session_id	null
		// meltdown	d253c96da70d53649af4df29d2d2eac5855cade4
		// reactor	8694f154769b1fe1a6c174209b7df65c6418e31c
		// testCount	9
		// user_id	608208
  }
  
  protected function renderJSON($data)
  {
  	$this->getResponse()->setContentType('application/json');
  	return $this->renderText(json_encode($data));
  }
}
