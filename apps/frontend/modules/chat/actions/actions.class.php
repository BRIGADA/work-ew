<?php

/**
 * chat actions.
 *
 * @package    edgeworld
 * @subpackage chat
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class chatActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->results = Doctrine::getTable('Chat')
  		->createQuery()
  		->select('room')
  		->distinct()
  		->groupBy('room')
  		->execute(array(), Doctrine::HYDRATE_SINGLE_SCALAR);
  	
  }
  
  public function executeRead(sfWebRequest $request)
  {
  	$this->room = $request->getParameter('room');
  	
  	$this->result = Doctrine::getTable('Chat')
  		->createQuery()
  		->where('room = ?', $this->room)
  		->limit(10)
  		->offset(100)
  		->orderBy('id DESC')
  		->fetchArray();
  	
  }
  
  public function executeNew(sfWebRequest $request)
  {
  	$room = $request->getParameter('room');
  	$this->forward404Unless($room);
  
  	$id = $request->getParameter('id');
  	$this->forward404Unless('id');
  	
  	$result = Doctrine::getTable('Chat')
  		->createQuery()
  		->where('room = ?', $room)
  		->andWhere('id > ?', $id)
  		->orderBy('id')
  		->fetchArray();
  	
  	$answer = array();
  	foreach ($result as $msg)
  	{
  		$answer[] = $this->getPartial('message', array('msg'=>$msg));
  	}
  	
  	$this->getResponse()->setContentType('application/json');  	
  	return $this->renderText(json_encode($answer));
  }
}
