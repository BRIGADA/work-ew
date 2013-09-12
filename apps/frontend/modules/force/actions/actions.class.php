<?php

/**
 * force actions.
 *
 * @package    edgeworld
 * @subpackage force
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class forceActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    //$this->forward('default', 'module');
    $this->tournaments = Doctrine::getTable('ForceTournament')
    	->createQuery()
    	->orderBy('end_at DESC')
    	->execute();
  }
  
  public function executeUpdate()
  {
  	$r = $this->getUser()->RGET('/api/player');
  	$this->redirectUnless($r, 'common/index');
  	
  	$data = json_decode($r,true);
  	
  	foreach ($data['response']['force_leaderboards']['recent_tournaments'] as $tournament)
  	{
  		$record = Doctrine::getTable('ForceTournament')->findOneById($tournament['id']);
  		if(!$record) {
  			$record = new ForceTournament();
  			$record->id = $tournament['id'];
  			// http://sector181.c1.galactic.wonderhill.com/api/force_tournaments/1052/prizing?meltdown=fabfd57ee56b21d481e319e2da3ab3c9846aa479&user%5Fid=608208&%5Fsession%5Fid=9b14ab36fb1325ceed9bef4ba6e3104d&reactor=8694f154769b1fe1a6c174209b7df65c6418e31c
  			$prizing = json_decode($this->getUser()->RGET("/api/force_tournaments/{$tournament['id']}/prizing"), true);
  			$record->bout_prizing = $prizing['response']['bout_prizing'];
  			$record->challenge_prizing = $prizing['response']['challenge_prizing'];  				
  		}
  		
  		$record->dates = $tournament['dates'];
  		$record->end_at = $tournament['end_at'];
  		$record->type = $tournament['type'];
  		$record->sector = $tournament['sector'];
  		$record->value_adjusments = $tournament['value_adjustments'];
  		$record->daily_prizing = $tournament['daily_prizing'];
  		$record->active_calculations = $tournament['active_calculations'];

  		$record->save();
  	}
  	
  	$this->redirect('force/index');
  }

  // /api/force_tournaments/1060/leaderboard
  //
  // count=8&reactor=8694f154769b1fe1a6c174209b7df65c6418e31c
  // round=1060
  // type=recent%5Ftournaments
  // page=1
  // %5Fsession%5Fid=2577b69264e2d53d5d5ebbd1be8aa087
  // user%5Fid=608208
  // meltdown=d339991cf322681d9dd438f96ff39c239952f4c0
  // include=1974957
  
  public function executeLeaderboard(sfWebRequest $request)
  {
  	$id = $request->getParameter('id');
  	$this->forward404Unless($id);
  	$this->tournament = Doctrine::getTable('ForceTournament')->findOneBy('id', $id);
  	$this->forward404Unless($this->tournament);
  	
  	$this->timestamp = Doctrine::getTable('ForceLeaderboard')
  		->createQuery()
  		->select('max(timestamp)')
  		->where('tournament_id = ?', $id)
  		->execute(null, Doctrine::HYDRATE_SINGLE_SCALAR);

  	if($this->timestamp == null)
  	{
  		$query = array();
  		$query['count'] = count($this->tournament->bout_prizing);
  		$query['page'] = 1;
  		$query['type'] = 'recent_tournaments';
  		$query['round'] = $id;
  		 
  		$remote = $this->getUser()->RGET("/api/force_tournaments/{$id}/leaderboard", $query);
  		$this->forward404Unless($remote, 'REMOTE FAILED');
  		
  		$remote = json_decode($remote);
  		
  		$this->data = Doctrine_Collection::create('ForceLeaderboard');
  		
  		foreach ($remote->response->rankings as $row)
  		{
  			$this->data[$row->rank]->tournament_id = $id;
  			$this->data[$row->rank]->timestamp = $remote->response->timestamp;
  			$this->data[$row->rank]->rank = $row->rank;
  			$this->data[$row->rank]->user_id = $row->id;
  			$this->data[$row->rank]->user_name = $row->name;
  			$this->data[$row->rank]->power = $row->power;  			
  		}
  		
  		$this->data->save();

  		$this->timestamp = $remote->response->timestamp;
  	}
  	else
  	{
	  	$this->data = Doctrine::getTable('ForceLeaderboard')
	  		->createQuery('a INDEXBY rank')
	  		->select('a.user_id, a.user_name, a.power')
	  		->where('a.tournament_id = ?', $id)
	  		->where('a.timestamp = ?', $this->timestamp)
	  		->execute();
  	}
  }
  
  public function executeCurrent(sfWebRequest $request)
  {
  	$id = $request->getParameter('id');
  	$this->forward404Unless($id);
  	$this->tournament = Doctrine::getTable('ForceTournament')->findOneBy('id', $id);
  	$this->forward404Unless($this->tournament);
  	
  	$query = array();
  	$query['count'] = count($this->tournament->bout_prizing);
  	$query['page'] = 1;
  	$query['type'] = 'recent_tournaments';
  	$query['round'] = $id;
  		
  	$remote = $this->getUser()->RGET("/api/force_tournaments/{$id}/leaderboard", $query);
  	$this->forward404Unless($remote, 'REMOTE FAILED');
  	
  	$remote = json_decode($remote);
  	
  	$this->data = Doctrine_Collection::create('ForceLeaderboard');
  	
  	foreach ($remote->response->rankings as $row)
  	{
  		$this->data[$row->rank]->tournament_id = $id;
  		$this->data[$row->rank]->timestamp = $remote->response->timestamp;
  		$this->data[$row->rank]->rank = $row->rank;
  		$this->data[$row->rank]->user_id = $row->id;
  		$this->data[$row->rank]->user_name = $row->name;
  		$this->data[$row->rank]->power = $row->power;
  	}
  	
  	$this->data->save();
  	
  	$this->getResponse()->setContentType('application/json');

  	return $this->renderText(json_encode($this->data->toArray()));
  	
  }
}
