<?php

/**
 * common actions.
 *
 * @package    edgeworld
 * @subpackage common
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class commonActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->history = Doctrine::getTable('ClientHistory')
  		->createQuery()
  		->orderBy('created_at DESC')
  		->limit(20)
  		->execute();
  	
  }
  
  public function executeSet(sfWebRequest $request)
  {
  	$url = $request->getParameter('url');
  	$this->forward404Unless($url);
  	 
  	$result = parse_url($url);
  	
  	if($result) {
  		$client_history = new ClientHistory();
  		$client_history->host = $result['host'];
  		
  		$this->getUser()->setAttribute('host', $result['host'], 'client');
  		parse_str($result['query'], $query);
  		foreach (array('meltdown', 'reactor', 'user_id', '_session_id') as $param) {
  			if(isset($query[$param])) {
  				$this->getUser()->setAttribute($param, $query[$param], 'client');
  				$client_history->$param = $query[$param];
  			}
  		}
  		$this->getUser()->setAttribute('testCount', 1, 'client');
  		$client_history->save();
  		
  		$this->getUser()->setAttribute('history_id', $client_history->id);
  		
  		$this->getUser()->setFlash('success', 'Параметры заданы');
  	}
  	else {
  		$this->getUser()->setFlash('error', 'Ошибка разбора URL');
  	}
  	
  	$this->redirect('common/index');
  }
  
  public function executeRGET(sfWebRequest $request)
  {
  	$path = $request->getParameter('path');
  	$this->forward404Unless($path);
  	
  	$query = $request->getParameter('query', array());
  	
  	$result = $this->getUser()->RGET($path, $query);
  	$this->forward404Unless($result, 'FETCH FAILED');
  	
    switch ($request->getParameter('decode'))
  	{
  		case 'base64':
  			$result = base64_decode($result);
  			break;
  		case 'amf':
  			$this->getContext()->getConfiguration()->registerZend();
  			$amf_stream = new Zend_Amf_Parse_InputStream($result);
  			$amf_parser = new Zend_Amf_Parse_Amf3_Deserializer($amf_stream);
  			$result = json_encode($amf_parser->readTypeMarker());
  			break;  			
  		default:
  			break;
  	}
  	
  	if(!is_null($request->getParameter('element'))){
  		$result = json_decode($result, true);
  		foreach (explode('/', $request->getParameter('element')) as $element) {
  			$result = $result[$element];
  		}
  		$result = json_encode($result);
  	}
  	 
  	$this->getResponse()->setContentType('application/json');
  	return $this->renderText($result);  	
  }
  
  public function executePlayer(sfWebRequest $request)
  {
  	$r = $this->getUser()->RGET('/api/player');
  	
  	if(!$r)
  	{
  		$this->getUser()->setFlash('error', 'failed get data /api/player');
  		$this->forward('common', 'index');
  	}
		
		$data = json_decode($r);
		
		$this->getUser()->setAttribute('resources', $data->response->base->resources);
		

		$bases = array();
		$bases[] = array('id'=>$data->response->base->id,'name'=>$data->response->base->name);
		
		foreach ($data->response->colonies as $colony)
		{
			$bases[] = array('id'=>$colony->id,'name'=>$colony->name);
		}
		
		$this->getUser()->setAttribute('bases', $bases, 'player');
		
		$this->getUser()->setAttribute('platinum', $data->response->platinum);
		$this->getUser()->setAttribute('sp', $data->response->sp);
		$this->getUser()->setAttribute('xp', $data->response->sp);
		$this->getUser()->setAttribute('level', $data->response->level);
		
//		$this->redirect('common/index');
		
  }
  
  public function executeSetClient(sfWebRequest $request)
  {
  	$client = $request->getParameter('client');
  	$this->forward404Unless($client);
  	
  	foreach ($client as $field => $value)
  	{
  		$this->getUser()->setAttribute($field, $value, 'client');
  	}
  	
  	$this->redirect('common/index');
  }
  
  public function executeResetTestCount()
  {
  	$this->getUser()->setAttribute('testCount', 1, 'client');
  	$this->redirect('common/index');
  }
  
  public function executeRPOST(sfWebRequest $request)
  {
  	$path = $request->getParameter('path');
  	$this->forward404Unless($path);
  	  	 
  }
  
  public function executeManifest(sfWebRequest $request)
  {
  	
  }
  
  public function executeUpdateEquipment(sfWebRequest $request)
  {
  	$data = $request->getParameter('data');
  	$this->forward404Unless($data);
  	
  	$equipment = Doctrine::getTable('Equipment')->findOneBy('type', $data['type']);
  	if(!$equipment) {
  		$equipment = new Equipment();
  		$equipment->type = $data['type'];
  		$equipment->save();
  	}
  	 
  	$levels = Doctrine::getTable('EquipmentLevel')
	  	->createQuery('INDEXBY level')
	  	->where('equipment_id = ?', $equipment->id)
	  	->orderBy('level')
	  	->execute();
  	 
  	foreach ($data['levels'] as $l)
  	{
  		$level = $levels[$l['level']];
  		$level->level = $l['level'];
  		$level->equipment_id = $equipment->id;
  		$level->tier = $l['tier'];
  		$level->time = $l['time'];
  		$level->upgrade_chance = $l['upgrade_chance'];
  		$level->require_g = $l['requirements']['resources']['gas'];
  		$level->require_e = $l['requirements']['resources']['energy'];
  		$level->require_u = $l['requirements']['resources']['uranium'];
  		$level->require_c = $l['requirements']['resources']['crystal'];
  		$level->require_s = $l['requirements']['sp'];
  		$level->stat_hp = $l['stats']['hp'];
  		$level->stat_range = $l['stats']['range'];
  		$level->stat_rate = $l['stats']['attack_rate'];
  		$level->stat_damage = $l['stats']['damage'];
  		$level->stat_targets = $l['stats']['simultaneous_targets'];
  		$level->stat_splash = $l['stats']['splash_radius'];
  		$level->stat_concussion = $l['stats']['concussion_effect'];
  		$level->stat_defense = $l['stats']['defense_exploder'];
  		$level->tags = (isset($l['tags']) && count($l['tags'])) ? $l['tags'][0] : NULL;
  	}
  	 
  	$levels->save();
  	
  	$this->getResponse()->setContentType('application/json');
  	return $this->renderText(json_encode(true));  	
  }
  
  public function executeProxyGET(sfWebRequest $request)
  {
  	$path = $request->getParameter('path');
  	$this->forward404Unless($path);
  	$query = $request->getParameter('query', array());
  	return $this->renderText($this->getUser()->ProxyGET($path, $query));
  }
  
  public function executeMeltdown(sfWebRequest $request)
  {
  	$value = $request->getParameter('value');
  	
  	$current = Doctrine::getTable('ClientHistory')
  		->createQuery()
  		->orderBy('id DESC')
  		->fetchOne();
  		
  	if(!is_null($value) && $current->meltdown != $value) {
  		$client_history = new ClientHistory();  		
  		$client_history->host = $current->host;
  		$client_history->meltdown = $value;
  		$client_history->reactor = $current->reactor;
  		$client_history->user_id = $current->user_id;
  		$client_history->_session_id = $current->_session_id;
  		$client_history->save();
  	}

  	$this->getResponse()->setContentType('application/json');
  	return $this->renderText(json_encode($this->getUser()->getAttribute('meltdown', null, 'client')));
  }

}
