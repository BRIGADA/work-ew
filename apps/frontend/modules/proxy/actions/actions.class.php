<?php

/**
 * proxy actions.
 *
 * @package    edgeworld
 * @subpackage proxy
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class proxyActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->types = Doctrine::getTable('Proxy')
  		->createQuery()
  		->select('type')
  		->distinct()
  		->execute(null, Doctrine::HYDRATE_SINGLE_SCALAR);
  	
  	$this->filter = $request->getParameter('filter');
  	$page = $request->getParameter('page', 1);
  	
  	$this->pager = new sfDoctrinePager('Proxy');
  	$this->pager->setPage($page);
  	$this->pager->getQuery()->orderBy('created_at');
  	if($this->filter) {
  		$this->pager->getQuery()->where('type = ?', $this->filter);  		
  		$this->pager->setParameter('filter', $this->filter);
  	}
  	$this->pager->init();
  }
  
  public function executeChat(sfWebRequest $request)
  {
  	$this->room = $request->getParameter('room');
  	
  	$this->rooms = Doctrine::getTable('Chat')
  		->createQuery()
  		->select('room')
  		->distinct()
  		->execute(null, Doctrine::HYDRATE_SINGLE_SCALAR);
  	
  	if(is_string($this->rooms)) {
  		$this->rooms = array($this->rooms);
  	}
  	
  	$this->messages = Doctrine::getTable('Chat')
  		->createQuery()
  		->orderBy('created_at DESC')
  		->limit(160)
  		->execute();
  }
  
}
