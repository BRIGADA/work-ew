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
  
  public function executeRPOST(sfWebRequest $request)
  {
  	$path = $request->getParameter('path');
  	$this->forward404Unless($path);
  	  	 
  }
}
