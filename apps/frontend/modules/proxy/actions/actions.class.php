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
//    $this->pager->
  	$this->pager->getQuery()->orderBy('id DESC');
    
  	if($this->filter) {
  		$this->pager->getQuery()->where('type = ?', $this->filter);  		
  		$this->pager->setParameter('filter', $this->filter);
  	}
  	$this->pager->init();
  }
 
}
