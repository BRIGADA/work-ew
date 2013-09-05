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
		
  	file_put_contents(sfConfig::get('sf_upload_dir').'/updated-equipment.json', $r);
		
  	$data = json_decode($r);
		$this->forwardUnless($data, 'common', 'index');
		
  	$this->results = $data->response->equipment;
  	

  	$this->types = array();

  	foreach($this->results as $equipment) {
  		if(!in_array($equipment->type, $this->types)) {
  			$this->types[] = $equipment->type;
  		}
  	}
  	
  	$this->manifest = Doctrine::getTable('Equipment')
  		->createQuery('e INDEXBY e.type')
  		->leftJoin('e.levels l INDEXBY l.level')
  		->whereIn('e.type', $this->types)
  		->fetchArray();
  	
  	$this->stats = array();
  	$this->levels = array();
  	$this->tiers = array();
  	
  	foreach($this->manifest as $row)
  	{
  		foreach($row['levels'] as $level)
  		{
  			if(!in_array($level['level'], $this->levels))
  			{
  				$this->levels[] = $level['level'];
  			}
  			if(!in_array($level['tier'], $this->tiers))
  			{
  				$this->tiers[] = $level['tier'];
  			}
  			foreach($level['stats'] as $stat => $value)
  			{
  				if(!in_array($stat, $this->stats))
  				{
  					$this->stats[] = $stat;
  				}
  			}
  		}
  	}
  	sort($this->stats);
  	sort($this->tiers);
  	sort($this->levels);
  	sort($this->types);
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
  	 	
  	return $this->renderJSON($result->response->job);
  }


  // /api/player/equipment/multidestroy
  //
  // _method	delete
  // _session_id	null
  // ids	8659633,8659622,8659629
  // meltdown	d253c96da70d53649af4df29d2d2eac5855cade4
  // reactor	8694f154769b1fe1a6c174209b7df65c6418e31c
  // testCount	8
  // user_id	608208  
  function executeMultidestroy(sfWebRequest $request)
  {
  	$ids = $request->getParameter('ids');
  	$this->forward404Unless($ids);
  	
  	$result = $this->getUser()->RPOST('/api/player/equipment/multidestroy', array('_method'=>'delete', 'ids' => implode(',', $ids)));
  	$this->forward404Unless($result);

  	return $this->renderJSON($result, false);
  }
  
 	// /api/player/equipment/8659665
 	//
	// _method	delete
	// _session_id	null
	// meltdown	d253c96da70d53649af4df29d2d2eac5855cade4
	// reactor	8694f154769b1fe1a6c174209b7df65c6418e31c
	// testCount	9
	// user_id	608208
  function executeDestroy(sfWebRequest $request)
  {
  	
 		$id = $request->getParameter('id');
 		$this->forward404Unless($id);
 		
 		$result = $this->getUser()->RPOST("/api/player/equipment/{$id}", array('_method'=>'delete'));
  	$this->forward404Unless($result);
  	
  	return $this->renderJSON($result, false);
  }
  
  // /api/player/equipment/11314627/repair
	// _method	post
	// _session_id	331f4b126435d0f374070f3d4ceeaeae
	// basis_id	2324999
	// meltdown	7dcedab87346ef34166bb77727c2b63a5b2d067b
	// reactor	8694f154769b1fe1a6c174209b7df65c6418e31c
	// testCount	433
	// user_id	608208
	public function executeRepair(sfWebRequest $request)
	{
		$id = $request->getParameter('id');
		$this->forward404Unless($id);
		
  	$query = array();
  	$query['basis_id'] = $this->getUser()->getAttribute('bases', array(), 'player')[0]['id'];
  	$query['_method'] = 'post';
		
		$result = $this->getUser()->RPOST("/api/player/equipment/{$id}/repair", $query);
		$this->forward404Unless($result);
		
		return $this->renderJSON($result, false);
	}
	
  
  protected function renderJSON($data, $encode = true)
  {
  	$this->getResponse()->setContentType('application/json');
 		return $this->renderText($encode ? json_encode($data) : $data);
  }
}
