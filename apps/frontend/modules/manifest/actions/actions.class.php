<?php

/**
 * manifest actions.
 *
 * @package    edgeworld
 * @subpackage manifest
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class manifestActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  
  public function executeItems(sfWebRequest $request)
  {
  	$this->filter = $request->getParameter('filter');
  	 
  	$this->items = Doctrine_Core::getTable('Item')
  	->createQuery('a')
  	->execute();
  
  	$this->tags = array();
  	foreach ($this->items as $item) {
  		if(is_array($item->tags))
  		foreach($item->tags as $tag) {
  			if(!in_array($tag, $this->tags)) {
  				$this->tags[] = $tag;
  			}
  		}
  	}
  	sort($this->tags);
  }
  
  public function executeItem(sfWebRequest $request)
  {
  	switch($request->getParameter('mode'))
  	{
  		case 'byID':
  			$id = $request->getParameter('id');
  			$this->forward404Unless($id);
  			$this->item = Doctrine_Core::getTable('Item')->find($id);  				
  			break;
  		case 'byType':
  			$type = $request->getParameter('type');
  			$this->forward404Unless($type);
  			$this->item = Doctrine_Core::getTable('Item')->findOneByType($type);  				
  			break;
  		default:
  			$this->forward404();
  	}

  	$this->forward404Unless($this->item);
  }
  
  public function executeItemUpdate(sfWebRequest $request)
  {
  	$this->forward404Unless($request->isMethod(sfRequest::POST));
  	$data = $request->getParameter('data', array());
  	 
  	$this->forward404Unless(isset($data['id']));
  	 
  	$record = Doctrine::getTable('Item')->findOneBy('id', $data['id']);
  	 
  	if(!$record) {
  		$record = new Item();
  		$record->id = $data['id'];
  	}
  
  	$record->type = $data['type'];
  
  	$record->permanent = $data['permanent'];
  
  	$record->tags = $data['tags'];
  
  	foreach ( array ('contents', 'boost_amount', 'boost_type', 'boost_percentage', 'resource_amount', 'resource_type') as $field ) {
  		if(isset($data[$field])) {
  			if($data[$field] !== $record->$field) {
  				$record->$field = $data[$field];
  			}
  		}
  		else {
  			$record->$field = NULL;
  		}
  	}
  
  	$record->save();
  		
  	// 		return sfView::RENDER_NONE;
  	 
  	$this->getResponse()->setContentType('application/json');
  	return $this->renderText(json_encode(true));
  }
  
  public function executeUnits()
  {
  	$this->units = Doctrine::getTable('Unit')->findAll();
  }
  public function executeUnitsCompare()
  {
  	$units = Doctrine::getTable('Unit')
  		->createQuery('u')
  		->leftJoin('u.levels l')
  		->orderBy('l.unit_id, l.level')
  		->execute();
  	
  	$this->chart_time = array();
  	$this->chart_uranium = array();
  	
  	foreach ($units as $unit)
  	{
  		$this->chart_time[$unit->type] = array();
  		$this->chart_uranium[$unit->type] = array();
  		foreach($unit->levels as $level)
  		{
  			$this->chart_time[$unit->type][] = intval($level->time);
  			$this->chart_uranium[$unit->type][] = intval($level->requirements['resources']['uranium']);
  		}
  	}
  }
  
  public function executeUnit(sfWebRequest $request)
  {
  	$type = $request->getParameter('type');
  	$this->forward404Unless($type);
  	
  	$this->unit = Doctrine::getTable('Unit')->findOneBy('type', $type);
  	$this->forward404Unless($this->unit);
  	
  	$this->items = array();
  	$this->buildings = array();
  	$this->research = array();
  	$this->stats = array();
  	
  	foreach($this->unit->levels as $l )
  	{
			if (isset ( $l->requirements ['buildings'] )) {
				foreach ( array_keys ( $l->requirements ['buildings'] ) as $k ) {
					if (! in_array ( $k, $this->buildings )) $this->buildings [] = $k;
				}
			}
			if (isset ( $l->requirements ['items'] )) {
				foreach ( array_keys ( $l->requirements ['items'] ) as $k ) {
					if (! in_array ( $k, $this->items )) $this->items [] = $k;
				}
			}
  		if (isset ( $l->requirements ['research'] )) {
				foreach ( array_keys ( $l->requirements ['research'] ) as $k ) {
					if (! in_array ( $k, $this->research )) $this->research [] = $k;
				}
			}
			
			foreach ( array_keys ( $l->stats ) as $k ) {
				if (! in_array ( $k, $this->stats )) $this->stats [] = $k;
			}				
  	}  	
  }
  public function executeCampaigns(sfWebRequest $request)
  {
  	$this->campaigns = Doctrine::getTable('Campaign')
  		->createQuery()
  		->orderBy('unlock_level')
  		->execute();  	
  }
  
  public function executeCampaign(sfWebRequest $request)
  {
  	$id = $request->getParameter('id');
  	$this->campaign = CampaignTable::getInstance()->find($id);
  	
  }
  
  public function executeCampaignUpdate(sfWebRequest $request)
  {
      $id = $request->getParameter('id');
      $campaign = Doctrine::getTable('Campaign')->find($id);
      if(!$campaign) {
          $campaign = new Campaign();
          $campaign->id = $id;
      }
      
      $campaign->name = $request->getParameter('name');
      $campaign->unlock_level = $request->getParameter('unlock_level');
      
      $campaign->stages->fromArray(json_decode($request->getParameter('stages', '[]'),true));
      
      $campaign->save();
      return sfView::HEADER_ONLY;      
  }
  
  public function executeSkills()
  {
  	$this->skills = Doctrine::getTable('Skill')->findAll();  
  }
  
  public function executeSkill(sfWebRequest $request)
  {
  	$type = $request->getParameter('type');
  	$this->forward404Unless($type);
  	
  	$this->skill = Doctrine::getTable('Skill')->findOneBy('type', $type);
  	$this->forward404Unless($this->skill);
  }
  
  public function executeGenerals()
  {
  	$this->generals = Doctrine::getTable('General')->findAll();
  }
  
  public function executeGeneralsCompare()
  {
  	$this->generals = Doctrine::getTable('General')->findAll();  	
  	
  	$this->stats = array();
  	foreach ($this->generals as $general)
  	{
  		foreach($general->stats as $stat)
  		{
  			$this->stats[$stat][$general->type] = $general->getStatData($stat);
  		}
  	}

  	$this->skills = Doctrine::getTable('Skill')->findAll();  	 
  }
  
  public function executeGeneral(sfWebRequest $request)
  {
  	$type = $request->getParameter('type');
  	$this->forward404Unless($type);
  	
  	$this->general = Doctrine::getTable('General')->findOneBy('type', $type);
  	$this->forward404Unless($this->general);
  }
  
  public function executeGeneralsUpdate(sfWebRequest $request)
  {
  	$this->forward404Unless($request->isMethod(sfWebRequest::POST), 'Only POST');
  	$data = $request->getParameter('general', 'Where data?');
  	$this->forward404Unless($data);
  	
  	$record = GeneralTable::getInstance()->findOneBy('type', $data['type']);
  	if(!$record)
  	{
  		$record = new General();
  	}
  	$record->fromArray($data);
  	$record->save();
  	return sfView::HEADER_ONLY;
  }
  
  public function executeResearches()
  {
  	$this->researches = ResearchTable::getInstance()->findAll();
  }
  
  public function executeResearch(sfWebRequest $request)
  {
  	$type = $request->getParameter('type');
  	$this->forward404Unless($type);
  	$this->research = ResearchTable::getInstance()->findOneByType($type);
  	$this->forward404Unless($this->research);
  	
  	$this->chart_resources = array();
  	foreach($this->research->resources as $resource)
  	{
  		$this->chart_resources[$resource] = $this->research->getResourceValues($resource);
  	}
  	
  	$this->requirements_buildings = array();
  	foreach($this->research->buildings as $building)
  	{
  		$this->requirements_buildings[$building] = $this->research->getBuildingValues($building);
  	}
  	
  	$this->requirements_items = array();
  	foreach($this->research->items as $item)
  	{
  		$this->requirements_items[$item] = $this->research->getItemValues($item);
  	}
  }
  
  public function executeDefenses()
  {
  	$this->defenses = DefenseTable::getInstance()->findAll();
  }

  public function executeEquipments()
  {
  	$this->equipments = Doctrine::getTable('Equipment')
  	 ->createQuery()
  	 ->orderBy('type')
  	 ->execute();
  }
  
  public function executeEquipmentUpdate(sfWebRequest $request)
  {
  	$this->forward404Unless($request->isMethod(sfWebRequest::POST), 'Only POST');
  	
  	$type = $request->getParameter('type');
  	$this->forward404Unless($type, 'Type?');
  	
  	$levels = $request->getParameter('levels', array());
  	$this->forward404Unless($levels, 'Levels?');

  	$record = EquipmentTable::getInstance()->findOneByType($type);
  	if(!$record) {
  		$record = new Equipment();
  		$record->type = $type;
  	}
  	
  	$record->levels->fromArray($levels);
  	
  	$record->save();
  	
  	return sfView::HEADER_ONLY;
  }
}
