<?php

/**
 * map actions.
 *
 * @package    edgeworld
 * @subpackage map
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mapActions extends sfActions {

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request) {
//  	if(!$this->getUser()->hasAttribute('maps', 'api'))
    {
      $r = $this->getUser()->RGET('/api/maps');
      $this->redirectUnless($r, 'common/index');
      $data = json_decode($r);
      $this->redirectUnless($data, 'common/index');
      $this->getUser()->setAttribute('maps', $data->response->maps, 'api');

      foreach ($data->response->maps as $map) {

        $record = Doctrine::getTable('Map')->find($map->id);
        if (!$record) {
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

  public function executeShow(sfWebRequest $request) {
    $id = $request->getParameter('id');

    $this->map = MapTable::getInstance()->find($id);

    $this->owners = MapNodeTable::getInstance()
            ->createQuery()
            ->distinct()
            ->select('owner')
            ->where('map_id = ?', $id)
            ->execute(null, Doctrine::HYDRATE_SINGLE_SCALAR);
    
    $this->collections = MapNodeTable::getInstance()
            ->createQuery()
            ->distinct()
            ->select('collection')
            ->where('map_id = ?', $id)
            ->execute(null, Doctrine::HYDRATE_SINGLE_SCALAR);
    
    $this->alliances_stat = MapNodeTable::getInstance()
            ->createQuery()
            ->select('collection_id, count(*) nodes, sum(level) score')
            ->where('map_id = ?', $id)
            ->andWhere('collection = ?', 'Alliance')
            ->groupBy('collection, collection_id')
            ->orderBy('score DESC')
            ->execute(null, Doctrine::HYDRATE_ARRAY_SHALLOW);
    
    $this->alliance_lookup = AllianceTable::getInstance()
            ->createQuery('a INDEXBY id')
            ->where('a.id IN (SELECT DISTINCT n.collection_id FROM MapNode n WHERE n.collection = ?)', 'Alliance')
            ->fetchArray();
  
//    $this->player_lookup = PlayerTable::getInstance()
//            ->createQuery('p INDEXBY id')
//            ->where('p.id IN (SELECT DISTINCT n.owner_id FROM MapNode n WHERE n.owner = ?)', 'Player')
//            ->fetchArray();

  }

  public function executeUpdate(sfWebRequest $request) {
    $id = $request->getParameter('id');
    $x = $request->getParameter('x');
    $y = $request->getParameter('y');

    $path = sprintf('/api/maps/%u/district/%u/%u', $id, $x, $y);

    $r = $this->getUser()->RGET($path);

    $data = json_decode($r, true);

    $map = MapTable::getInstance()->find($id);

    $nodes = MapNodeTable::getInstance()
            ->createQuery('INDEXBY id')
            ->where('map_id = ?', $id)
            ->andWhere('x >= ?', $x * $map->chunk_size)
            ->andWhere('x < ?', ($x + 1) * $map->chunk_size)
            ->andWhere('y >= ?', $y * $map->chunk_size)
            ->andWhere('y < ?', ($y + 1) * $map->chunk_size)
            ->execute();

    foreach ($data['response']['nodes'] as $n) {
      $record = $nodes->get($n['id']);
      $record->id = $n['id'];
      $record->map_id = $id;
      $record->x = $n['x'];
      $record->y = $n['y'];
      $record->level = $n['l'];
      $record->owner = $n['ot'];
      $record->owner_id = $n['oid'];
      $record->collection = $n['ct'];
      $record->collection_id = $n['cid'];
    }
    
    $alliances = AllianceTable::getInstance()
            ->createQuery('INDEXBY id')
            ->whereIn('id', array_keys($data['response']['alliance_lookup']))
            ->execute();

    foreach ($data['response']['alliance_lookup'] as $k => $n) {
      $record = $alliances->get($k);
      $record->id = $k;
      $record->name = $n;
    }

    $alliances->save();

    $players = PlayerTable::getInstance()
            ->createQuery('INDEXBY id')
            ->whereIn('id', array_keys($data['response']['player_lookup']))
            ->execute();

    foreach ($data['response']['player_lookup'] as $k => $n) {
      $record = $players->get($k);
      $record->id = $k;
      $record->name = $n;
    }

    $players->save();

    $nodes->save();


    $this->getResponse()->setContentType('application/json');
    return $this->renderText(json_encode($data));
  }

}
