<?php

/**
 * equipment actions.
 *
 * @package    edgeworld
 * @subpackage equipment
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class equipmentActions extends sfActions
{

    /**
     * Executes index action
     *
     * @param sfRequest $request
     *            A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
        // $data = file_get_contents(sfConfig::get('sf_upload_dir').'/api-player-equipment.json');
        $r = $this->getUser()->RGET('/api/player/equipment');
        $this->forwardUnless($r, 'common', 'index');
        
        $data = json_decode($r);
        $this->forwardUnless($data, 'common', 'index');
        
        $this->results = $data->response->equipment;
        
        $this->types = array();
        
        foreach ($this->results as $equipment) {
            if (! in_array($equipment->type, $this->types)) {
                $this->types[] = $equipment->type;
            }
        }
        
        sort($this->types);
        
        $this->manifest = Doctrine::getTable('Equipment')->createQuery('e INDEXBY e.type')
            ->leftJoin('e.levels l INDEXBY l.level')
            ->whereIn('e.type', $this->types)
            ->fetchArray();
        
        $this->stats = array();
        $this->levels = array();
        $this->tiers = array();
        
        foreach ($this->manifest as $row) {
            foreach ($row['levels'] as $level) {
                if (! in_array($level['level'], $this->levels)) {
                    $this->levels[] = $level['level'];
                }
                if (! in_array($level['tier'], $this->tiers)) {
                    $this->tiers[] = $level['tier'];
                }
                foreach ($level['stats'] as $stat => $value) {
                    if (! in_array($stat, $this->stats)) {
                        $this->stats[] = $stat;
                    }
                }
            }
        }
        sort($this->stats);
        sort($this->tiers);
        sort($this->levels);
        sort($this->types);
    }

    function executeUpgrade(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
        $this->forward404Unless($id);
        
        $query = array();
        $query['basis_id'] = $this->getUser()->getBaseID();
        $query['_method'] = 'post';
        
        $r = $this->getUser()->RPOST(sprintf('/api/player/equipment/%s/instant_upgrade', $id), $query);
        $this->forward404Unless($r);
        
        $result = json_decode($r, true);
        
        $this->forward404Unless(isset($result['response']['job']));
        return $this->renderJSON($result['response']['job']);
    }
    
    // /api/player/equipment/multidestroy
    //
    // _method delete
    // _session_id null
    // ids 8659633,8659622,8659629
    // meltdown d253c96da70d53649af4df29d2d2eac5855cade4
    // reactor 8694f154769b1fe1a6c174209b7df65c6418e31c
    // testCount 8
    // user_id 608208
    function executeMultidestroy(sfWebRequest $request)
    {
        $ids = $request->getParameter('ids');
        $this->forward404Unless($ids);
        
        $result = $this->getUser()->RPOST('/api/player/equipment/multidestroy', array(
            '_method' => 'delete',
            'ids' => implode(',', $ids)
        ));
        $this->forward404Unless($result);
        
        return $this->renderJSON($result, false);
    }
    
    // /api/player/equipment/8659665
    //
    // _method delete
    // _session_id null
    // meltdown d253c96da70d53649af4df29d2d2eac5855cade4
    // reactor 8694f154769b1fe1a6c174209b7df65c6418e31c
    // testCount 9
    // user_id 608208
    function executeDestroy(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
        $this->forward404Unless($id);
        
        $result = $this->getUser()->RPOST("/api/player/equipment/{$id}", array(
            '_method' => 'delete'
        ));
        $this->forward404Unless($result);
        
        return $this->renderJSON($result, false);
    }
    
    // /api/player/equipment/11314627/repair
    // _method post
    // _session_id 331f4b126435d0f374070f3d4ceeaeae
    // basis_id 2324999
    // meltdown 7dcedab87346ef34166bb77727c2b63a5b2d067b
    // reactor 8694f154769b1fe1a6c174209b7df65c6418e31c
    // testCount 433
    // user_id 608208
    public function executeRepair(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
        $this->forward404Unless($id);
        
        $query = array();
        $query['basis_id'] = $this->getUser()->getBaseID();
        $query['_method'] = 'post';
        
        $result = $this->getUser()->RPOST("/api/player/equipment/{$id}/repair", $query);
        $this->forward404Unless($result);
        
        return $this->renderJSON($result, false);
    }
    
    // /api/player/craft
    // _session_id=cf221a0bea87e72192a7694ccdcb6e1e
    // input=[{"type":"equipment","id":12321596},{"type":"equipment","id":12321118},{"type":"equipment","id":12321668},{"type":"equipment","id":12321710},{"type":"equipment","id":12321730}]
    // meltdown=308073d8bcd84726b10cfc104e2cd44c1e6934bc
    // name=rarebox1b
    // reactor=8694f154769b1fe1a6c174209b7df65c6418e31c
    // testCount=4
    // user_id=608208
    public function executeCraft(sfWebRequest $request)
    {
        $name = $request->getParameter('name');
        $this->forward404Unless($name);
        
        $ids = $request->getParameter('ids');
        $this->forward404Unless($ids);
        
        $input = array();
        foreach($ids as $id)
        {
            $input[] = array('type'=>'equipment', 'id'=>intval($id));
        } 
        
        $query = array('name'=>$name, 'input'=>json_encode($input));
        
        $result = $this->getUser()->RPOST('/api/player/craft', $query);
        
        return $this->renderJSON($result, false);
    }    
    
    protected function renderJSON($data, $encode = true)
    {
        $this->getResponse()->setContentType('application/json');
        return $this->renderText($encode ? json_encode($data) : $data);
    }
    
    public function executeAuto(sfWebRequest $request) {
        $query = array();
        $query['cmd'] = 'autoequipment_stat';
        $query['user_id'] = $this->getUser()->getAttribute('user_id', null, 'player/data');
        
        $r = $this->getUser()->proxy($query);
        $this->forward404Unless($r);        
        
        if($request->isXmlHttpRequest()) {
            $this->getResponse()->setContentType('application/json');
            return $this->renderText($r);
        }
        $this->current = json_decode($r, true);
//        ksort($this->current);
    }
}
