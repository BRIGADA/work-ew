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
  	$this->meltdowns = Doctrine::getTable('Meltdown')
  		->createQuery()
  		->orderBy('id DESC')
  		->limit(10)
  		->execute();
  }
  
  public function executeSet(sfWebRequest $request)
  {
  	$url = $request->getParameter('url');
  	$this->forward404Unless($url);
  	 
  	$result = parse_url($url);
  	
  	if($result) {
  		
  		parse_str($result['query'], $query);

  		$this->getUser()->setAttribute('host', $result['host'], 'playerVO');
  		$this->getUser()->setAttribute('_session_id', $query['_session_id'], 'playerVO');
  		$this->getUser()->setAttribute('reactor', $query['reactor'], 'playerVO');
  		$this->getUser()->setAttribute('user_id', $query['user_id'], 'playerVO');
  		$this->getUser()->setAttribute('testCount', 1, 'playerVO');
  		
  		if(MeltdownTable::getLast() != $query['meltdown']) {
	  		$meltdown = new Meltdown();
	  		$meltdown->value = $query['meltdown'];
	  		$meltdown->save();
  		}
  		
  		$r = $this->getUser()->RGET('/api/player');
  		 
  		if($r) {
	  		$data = json_decode($r);
	  		$this->getUser()->setAttribute('resources', $data->response->base->resources);
	  		$bases = array();
	  		$bases[] = array('id'=>$data->response->base->id,'name'=>$data->response->base->name);
	  		
	  		foreach ($data->response->colonies as $colony) {
	  			$bases[] = array('id'=>$colony->id,'name'=>$colony->name);
	  		}
	  		
	  		$this->getUser()->setAttribute('bases', $bases, 'player');
	  		$this->getUser()->setAttribute('platinum', $data->response->platinum);
	  		$this->getUser()->setAttribute('sp', $data->response->sp);
	  		$this->getUser()->setAttribute('xp', $data->response->sp);
	  		$this->getUser()->setAttribute('level', $data->response->level);
	  		
	  		$this->getUser()->setFlash('success', 'Параметры заданы');
  		}
  		else {
  			$this->getUser()->setFlash('error', 'Ошибка получения /api/player');  				
  		}
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
  	
  	$result = $request->getParameter('use_proxy', false) ? $this->getUser()->ProxyGET($path, $query) : $this->getUser()->RGET($path, $query);
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
  
  public function executeSetClient(sfWebRequest $request)
  {
  	$client = $request->getParameter('client');
  	$this->forward404Unless($client);
  	
  	$this->getUser()->setAttribute('host', $client['host'], 'playerVO');
  	$this->getUser()->setAttribute('_session_id', $client['_session_id'], 'playerVO');
  	$this->getUser()->setAttribute('reactor', $client['reactor'], 'playerVO');
  	$this->getUser()->setAttribute('user_id', $client['user_id'], 'playerVO');
  	$this->getUser()->setAttribute('testCount', $client['testCount'], 'playerVO');
  	 
  	$meltdown = new Meltdown();
  	$meltdown->value = $client['meltdown'];
  	$meltdown->save();  	 

  	$this->redirect('common/index');
  }
  
  public function executeResetTestCount()
  {
  	$this->getUser()->setAttribute('testCount', 1, 'client');
  	$this->redirect('common/index');
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
  	
  	$current = MeltdownTable::getLast();
  		
  	if(!is_null($value) && $current != $value) {
  		$actual = new Meltdown();
  		$actual->value = $value;
  		$actual->save();
  	}

  	$this->getResponse()->setContentType('application/json');
  	return $this->renderText(json_encode($value));
  }

}
