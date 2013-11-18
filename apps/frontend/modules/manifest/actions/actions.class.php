<?php

/**
 * manifest actions.
 *
 * @package    edgeworld
 * @subpackage manifest
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class manifestActions extends sfActions {

  /**
   * Executes index action
   *
   * @param sfRequest $request
   *            A request object
   */
  public function executeIndex() {
    $this->buildings = Doctrine::getTable('Building')->count();
  }

  public function executeTranslation($request) {
//        parent::execute($request);
    return sfView::HEADER_ONLY;
  }

  public function executeTrans(sfWebRequest $request) {
    $locale = $request->getParameter('locale', ['en', 'ru']);
    foreach ($locale as $lang) {
      $r = $this->getUser()->PGET('/api/manifest/translations', ['locale' => $lang], true);
      $this->forward404Unless($r, "{$lang} FAILED");

      $r = preg_replace('/<<p>><\/<p>>/', '', $r);
      $r = preg_replace('/#ITEM-LIST#/', 'ITEM_LIST', $r);

      file_put_contents(sfConfig::get('sf_upload_dir') . "/trans.{$lang}.xml", $r);
    }

    $this->getResponse()->setContentType('application/json');
    return $this->renderText(json_encode(true));
  }

  public function executeItems(sfWebRequest $request) {

    if ($request->isXmlHttpRequest() || $request->getParameter('json')) {
      $result = Doctrine::getTable('Item')
              ->createQuery('INDEXBY type')
              ->orderBy('type')
              ->fetchArray();
//                    ->findAll(Doctrine::HYDRATE_ARRAY);

      $this->getResponse()->setContentType('application/json');
      return $this->renderText(json_encode($result, JSON_NUMERIC_CHECK));
    }

    $this->filter = $request->getParameter('filter');

    $this->items = Doctrine_Core::getTable('Item')->createQuery('a')->execute();

    $this->tags = array();
    foreach ($this->items as $item) {
      if (is_array($item->tags))
        foreach ($item->tags as $tag) {
          if (!in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
          }
        }
    }
    sort($this->tags);
  }

  public function executeItem(sfWebRequest $request) {
    switch ($request->getParameter('mode')) {
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

  public function executeUnits(sfWebRequest $request) {
    if ($request->isXmlHttpRequest()) {
      $result = Doctrine::getTable('Unit')
              ->createQuery('u INDEXBY type')
              ->leftJoin('u.levels l')
              ->orderBy('u.id, l.level')
              ->fetchArray();
      $this->getResponse()->setContentType('application/json');
      return $this->renderText(json_encode($result, JSON_NUMERIC_CHECK));
    }

    $this->units = Doctrine::getTable('Unit')->findAll();
  }

  public function executeUnitsCompare() {
    $units = Doctrine::getTable('Unit')->createQuery('u')
            ->leftJoin('u.levels l')
            ->orderBy('l.unit_id, l.level')
            ->execute();

    $this->chart_time = array();
    $this->chart_uranium = array();

    foreach ($units as $unit) {
      $this->chart_time[$unit->type] = array();
      $this->chart_uranium[$unit->type] = array();
      foreach ($unit->levels as $level) {
        $this->chart_time[$unit->type][] = intval($level->time);
        $this->chart_uranium[$unit->type][] = intval($level->requirements['resources']['uranium']);
      }
    }
  }

  public function executeUnit(sfWebRequest $request) {
    $type = $request->getParameter('type');
    $this->forward404Unless($type);

    $this->unit = Doctrine::getTable('Unit')->findOneBy('type', $type);
    $this->forward404Unless($this->unit);

    $this->items = array();
    $this->buildings = array();
    $this->research = array();
    $this->stats = array();

    foreach ($this->unit->levels as $l) {
      if (isset($l->requirements['buildings'])) {
        foreach (array_keys($l->requirements['buildings']) as $k) {
          if (!in_array($k, $this->buildings))
            $this->buildings[] = $k;
        }
      }
      if (isset($l->requirements['items'])) {
        foreach (array_keys($l->requirements['items']) as $k) {
          if (!in_array($k, $this->items))
            $this->items[] = $k;
        }
      }
      if (isset($l->requirements['research'])) {
        foreach (array_keys($l->requirements['research']) as $k) {
          if (!in_array($k, $this->research))
            $this->research[] = $k;
        }
      }

      foreach (array_keys($l->stats) as $k) {
        if (!in_array($k, $this->stats))
          $this->stats[] = $k;
      }
    }
  }

  public function executeCampaigns(sfWebRequest $request) {
    if($request->isXmlHttpRequest() || $request->getParameter('json')) {
      $result = Doctrine::getTable('Campaign')
              ->createQuery('c INDEXBY name')
              ->leftJoin('c.stages s')
              ->leftJoin('s.units u')
              ->orderBy('c.name, s.id, u.id')
              ->fetchArray();
      $this->getResponse()->setContentType('application/json');
      return $this->renderText(json_encode($result, JSON_NUMERIC_CHECK));
    }
    
    $this->campaigns = Doctrine::getTable('Campaign')->createQuery()
            ->orderBy('unlock_level')
            ->execute();
  }

  public function executeCampaign(sfWebRequest $request) {
    $id = $request->getParameter('id');
    $this->campaign = CampaignTable::getInstance()->find($id);
  }

  public function executeCampaignUpdate(sfWebRequest $request) {
    $this->forward404Unless($request->hasParameter('value'));
    
    $campaign = json_decode($request->getParameter('value'), true);
    
    $record = Doctrine::getTable('Campaign')->findOneBy('id', $campaign['id']);
    if(!$record) {
      $record = new Campaign();
    }
    $record->fromArray($campaign);
    $record->save();
    
    return sfView::HEADER_ONLY;
  }

  public function executeSkills(sfWebRequest $request) {
    if($request->isXmlHttpRequest() || $request->getParameter('json')) {
      $result = Doctrine::getTable('Skill')
              ->createQuery('s INDEXBY type')
              ->leftJoin('s.levels l')
              ->orderBy('s.type, l.level')
              ->fetchArray();
      $this->getResponse()->setContentType('application/json');
      return $this->renderText(json_encode($result, JSON_NUMERIC_CHECK));
    }
    
    $this->skills = Doctrine::getTable('Skill')->findAll();
  }

  public function executeSkill(sfWebRequest $request) {
    $type = $request->getParameter('type');
    $this->forward404Unless($type);

    $this->skill = Doctrine::getTable('Skill')->findOneBy('type', $type);
    $this->forward404Unless($this->skill);
  }
  
  public function executeUpdate(sfWebRequest $request) {
    $this->forward404Unless($request->hasParameter('value'));    
    $data = json_decode($request->getParameter('value'), true);
    
    $class = $request->getParameter('class');
    $this->forward404Unless($class);
    
    $key = $request->getParameter('key');
    $this->forward404Unless($key);
    
    $record = Doctrine::getTable($class)->findOneBy($key, $data[$key]);
    if(!$record) {
      $record = new $class();
    }
    $record->fromArray($data);
    $record->save();
    
    return sfView::HEADER_ONLY;
  }
  
  public function executeGenerals(sfWebRequest $request) {
    if($request->isXmlHttpRequest() || $request->getParameter('json')) {
      $result = Doctrine::getTable('General')
              ->createQuery('g INDEXBY type')
              ->leftJoin('g.levels l')
              ->orderBy('l.level')
              ->fetchArray();
      $this->getResponse()->setContentType('application/json');
      return $this->renderText(json_encode($result, JSON_NUMERIC_CHECK));
    }
    $this->generals = Doctrine::getTable('General')->findAll();
  }

  public function executeGeneralsCompare() {
    $this->generals = Doctrine::getTable('General')->findAll();

    $this->stats = array();
    foreach ($this->generals as $general) {
      foreach ($general->stats as $stat) {
        $this->stats[$stat][$general->type] = $general->getStatData($stat);
      }
    }

    $this->skills = Doctrine::getTable('Skill')->findAll();
  }

  public function executeGeneral(sfWebRequest $request) {
    $type = $request->getParameter('type');
    $this->forward404Unless($type);

    $this->general = Doctrine::getTable('General')->findOneBy('type', $type);
    $this->forward404Unless($this->general);
  }

  public function executeResearches(sfWebRequest $request) {
    if($request->isXmlHttpRequest()) {
      $result = Doctrine::getTable('Research')
              ->createQuery('r INDEXBY type')
              ->leftJoin('r.levels l')
              ->orderBy('r.type, l.level')
              ->fetchArray();
      $this->getResponse()->setContentType('application/json');
      return $this->renderText(json_encode($result, JSON_NUMERIC_CHECK));
    }
    
    $this->researches = ResearchTable::getInstance()->findAll();
  }

  public function executeResearch(sfWebRequest $request) {
    $type = $request->getParameter('type');
    $this->forward404Unless($type);
    $this->research = ResearchTable::getInstance()->findOneByType($type);
    $this->forward404Unless($this->research);

    $this->chart_resources = array();
    foreach ($this->research->resources as $resource) {
      $this->chart_resources[$resource] = $this->research->getResourceValues($resource);
    }

    $this->requirements_buildings = array();
    foreach ($this->research->buildings as $building) {
      $this->requirements_buildings[$building] = $this->research->getBuildingValues($building);
    }

    $this->requirements_items = array();
    foreach ($this->research->items as $item) {
      $this->requirements_items[$item] = $this->research->getItemValues($item);
    }
  }

  public function executeDefenses(sfWebRequest $request) {
    if($request->isXmlHttpRequest()) {
      $result = Doctrine::getTable('Defense')
              ->createQuery('d INDEXBY type')
              ->leftJoin('d.levels l')
              ->orderBy('d.type, l.level')
              ->fetchArray();
      $this->getResponse()->setContentType('application/json');
      return $this->renderText(json_encode($result, JSON_NUMERIC_CHECK));
    }
    $this->defenses = DefenseTable::getInstance()->findAll();
  }

  public function executeEquipments() {
    $this->equipments = Doctrine::getTable('Equipment')->createQuery()
            ->orderBy('type')
            ->execute();
  }

  public function executeEquipmentUpdate(sfWebRequest $request) {
    $this->forward404Unless($request->isMethod(sfWebRequest::POST), 'Only POST');

    $type = $request->getParameter('type');
    $this->forward404Unless($type, 'Type?');

    $levels = $request->getParameter('levels', array());
    $this->forward404Unless($levels, 'Levels?');

    $record = EquipmentTable::getInstance()->findOneByType($type);
    if (!$record) {
      $record = new Equipment();
      $record->type = $type;
    }

    $record->levels->fromArray($levels);

    $record->save();

    return sfView::HEADER_ONLY;
  }

  public function executeEquipment(sfWebRequest $request) {
    $this->equipment = Doctrine::getTable('Equipment')->findOneBy('type', $request->getParameter('type'));
    $this->levels = Doctrine::getTable('EquipmentLevel')->createQuery()
            ->where('equipment_id = ?', $this->equipment->id)
            ->orderBy('level')
            ->execute();

    $this->resources = array();
    $this->stats = array();
    $this->nonnumeric = array();

    foreach ($this->levels as $level) {
      foreach ($level->requirements['resources'] as $type => $value) {
        if (!in_array($type, $this->resources) && $value) {
          $this->resources[] = $type;
        }
      }

      foreach ($level->stats as $type => $value) {
        if (!in_array($type, $this->stats) && ($value != 'false') && $value) {
          if (!is_numeric($value) && !in_array($type, $this->nonnumeric))
            $this->nonnumeric[] = $type;
          $this->stats[] = $type;
        }
      }
    }
  }

  public function executeRecipes(sfWebRequest $request) {
    if($request->isXmlHttpRequest()) {
      $result = Doctrine::getTable('CraftingRecipe')
              ->createQuery('INDEXBY name')
              ->fetchArray();
      $this->getResponse()->setContentType('application/json');
      return $this->renderText(json_encode($result, JSON_NUMERIC_CHECK));
    }
    $this->recipes = Doctrine::getTable('CraftingRecipe')->createQuery()->orderBy('id')->execute();
  }

  public function executeRecipe(sfWebRequest $request) {
    $name = $request->getParameter('name');
    $this->forward404Unless($name);

    $this->recipe = Doctrine::getTable('CraftingRecipe')->findOneBy('name', $name);
    $this->forward404Unless($this->recipe);
  }

  public function executeBuildings(sfWebRequest $request) {
    if ($request->isXmlHttpRequest() || $request->getParameter('json')) {
      $result = Doctrine::getTable('Building')
              ->createQuery('b INDEXBY type')
              ->leftJoin('b.levels l INDEXBY l.level')
              ->fetchArray();

      foreach ($result as &$b) {
        $b['size'] = array(intval($b['size_x']), intval($b['size_y']));
        unset($b['size_x'], $b['size_y']);
        unset($b['id']);
        foreach ($b['levels'] as &$l) {
          unset($l['building_id']);
        }
      }
      $this->getResponse()->setContentType('application/json');
      return $this->renderText(json_encode($result));
    }
    $this->buildings = Doctrine::getTable('Building')->findAll();
  }

// https://kabam1-a.akamaihd.net/edgeworld/images/buildings/antiaircraftturret.png
  public function executeBuilding(sfWebRequest $request) {
    $type = $request->getParameter('type');
    $this->forward404Unless($type);

    $this->building = Doctrine::getTable('Building')
            ->createQuery('b')
            ->leftJoin('b.levels l INDEX BY l.level')
//                ->select('b.*, l.*')
            ->where('b.type = ?', $type)
            ->fetchOne();

    if ($request->isXmlHttpRequest()) {
      $this->getResponse()->setContentType('application/json');
      return $this->renderText(json_encode($this->building->toArray(), JSON_NUMERIC_CHECK));
    }

    $this->stats = array();

    foreach ($this->building->levels as $level) {
      foreach ($level->stats as $stat => $value) {
        if (!in_array($stat, $this->stats) && ($value != 0)) {
          $this->stats[] = $stat;
        }
      }
    }
  }

  public function executeStore(sfWebRequest $request)
  {
    if($request->isXmlHttpRequest()) {
      $result = Doctrine::getTable('Store')
              ->createQuery('INDEXBY id')
              ->orderBy('id')
              ->fetchArray();
      $this->getResponse()->setContentType('application/json');
      return $this->renderText(json_encode($result, JSON_NUMERIC_CHECK));
              
    }
  }

  public function executeFile() {
    $filename = sfConfig::get('sf_upload_dir') . '/-api-manifest.amf';
    $f = file_get_contents($filename);
    $this->getResponse()->setContentType('application/json');
    return $this->renderText($f);
  }

}
