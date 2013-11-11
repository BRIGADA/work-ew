<?php

/**
 * map actions.
 *
 * @package    edgeworld
 * @subpackage map
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mapActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
//  	if(!$this->getUser()->hasAttribute('maps', 'api'))
  	{
	  	$r = $this->getUser()->RGET('/api/maps');
	  	$this->redirectUnless($r, 'common/index');  	
	  	$data = json_decode($r);
	  	$this->redirectUnless($data, 'common/index');
	  	$this->getUser()->setAttribute('maps', $data->response->maps, 'api');
        
        foreach($data->response->maps as $map) {

          $record = Doctrine::getTable('Map')->find($map->id);
          if(!$record) {
            $record = new Map();
            $record->id = $map->id;
          }
          $record->width = $map->width;
          $record->height = $map->height;
          $record->sector = $map->sector;
          $record->chunk_size = $map->chunk_size;
          $record->active = $map->active;
          $record->type = $map->type;
          $record->maximum_node_level = $map->maximum_node_level;
          $record->max_territory_limit = $map->max_territory_limit;
          $record->upgrade_costs = $map->upgrade_costs;
          $record->outpost_levels = $map->outpost_levels;
          
          $record->save();
        }
	  	
  	}
	$this->result = $this->getUser()->getAttribute('maps', null, 'api');
  }

  public function executeShow(sfWebRequest $request)
  {
  	$pos = $request->getParameter('pos');
  	$this->map = $this->getUser()->getAttribute('maps', null, 'api')[$pos];
  }
  
}
