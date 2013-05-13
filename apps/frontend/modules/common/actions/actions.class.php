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
    //$this->forward('default', 'module');
  }
  
  public function executeSet(sfWebRequest $request)
  {
  	$url = $request->getParameter('url');
  	$this->forward404Unless($url);
  	 
  	$result = parse_url($url);
  	
  	if($result) {
  		$this->getUser()->setAttribute('host', $result['host'], 'client');
  		parse_str($result['query'], $query);
  		foreach (array('meltdown', 'reactor', 'user_id', '_session_id', 'testCount') as $param) {
  			if(isset($query[$param])) {
  				$this->getUser()->setAttribute($param, $query[$param], 'client');
  			}
  		}
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
  	
  	$decode = $request->getParameter('decode') == true;
  	
  	$query = $request->getParameter('query', array());
  	
  	$result = $this->getUser()->RGET($path, $query);
  	
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
}
