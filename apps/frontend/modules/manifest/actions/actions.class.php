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

    public function executeItemUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $id = $request->getParameter('id');
        $this->forward404Unless($id);

        $record = Doctrine::getTable('Item')->find($id);
        if (!$record) {
            $record = new Item();
            $record->id = $id;
        }

        $record->type = $request->getParameter('type');
        $record->boost_amount = $request->getParameter('boost_amount');
        $record->boost_percentage = $request->getParameter('boost_percentage');
        $record->boost_type = $request->getParameter('boost_type');
        $record->contents = $request->getParameter('contents');
        $record->permanent = filter_var($request->getParameter('permanent', 'false'), FILTER_VALIDATE_BOOLEAN);
        $record->resource_amount = $request->getParameter('resource_amount');
        $record->resource_type = $request->getParameter('resource_type');
        $record->tags = $request->getParameter('tags');

        if (is_array($record->contents)) {
            foreach ($record->contents as &$a) {
                $a['quantity'] = intval($a['quantity']);
                if (isset($a['item_id']))
                    $a['item_id'] = intval($a['item_id']);
            }
        }

        $record->save();

        return sfView::HEADER_ONLY;
    }

    public function executeUnits() {
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

    public function executeUnitUpdate(sfWebRequest $request) {
        $type = $request->getParameter('type');
        $this->forward404Unless($type);

//        $record = 
    }

    public function executeCampaigns(sfWebRequest $request) {
        $this->campaigns = Doctrine::getTable('Campaign')->createQuery()
                ->orderBy('unlock_level')
                ->execute();
    }

    public function executeCampaign(sfWebRequest $request) {
        $id = $request->getParameter('id');
        $this->campaign = CampaignTable::getInstance()->find($id);
    }

    public function executeCampaignUpdate(sfWebRequest $request) {
        $id = $request->getParameter('id');
        $campaign = Doctrine::getTable('Campaign')->find($id);
        if (!$campaign) {
            $campaign = new Campaign();
            $campaign->id = $id;
        }

        $campaign->name = $request->getParameter('name');
        $campaign->unlock_level = $request->getParameter('unlock_level');

        $campaign->stages->fromArray(json_decode($request->getParameter('stages', '[]'), true));

        $campaign->save();
        return sfView::HEADER_ONLY;
    }

    public function executeSkills() {
        $this->skills = Doctrine::getTable('Skill')->findAll();
    }

    public function executeSkill(sfWebRequest $request) {
        $type = $request->getParameter('type');
        $this->forward404Unless($type);

        $this->skill = Doctrine::getTable('Skill')->findOneBy('type', $type);
        $this->forward404Unless($this->skill);
    }

    public function executeGenerals() {
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

    public function executeGeneralUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfWebRequest::POST), 'Only POST');
        $type = $request->getParameter('type');
        $this->forward404Unless($type);

        $record = GeneralTable::getInstance()->findOneBy('type', $type);
        if (!$record) {
            $record = new General();
            $record->type = $type;
        }
        $this->getLogger()->debug(var_export($request->getParameter('levels'), true));
        $record->levels->fromArray(json_decode(json_encode($request->getParameter('levels'), JSON_NUMERIC_CHECK), true));
        $record->save();
        return sfView::HEADER_ONLY;
    }

    public function executeResearches() {
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

    public function executeDefenses() {
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

    public function executeRecipes() {
        $this->recipes = Doctrine::getTable('CraftingRecipe')->createQuery()->orderBy('id')->execute();
    }

    public function executeRecipeUpdate(sfWebRequest $request) {
        $name = $request->getParameter('name');
        $this->forward404Unless($name);

        $recipe = Doctrine::getTable('CraftingRecipe')->findOneBy('name', $name);
        if (!$recipe) {
            $recipe = new CraftingRecipe();
            $recipe->name = $name;
        }

        $recipe->inputs = $request->getParameter('inputs');
        $recipe->outputs = $request->getParameter('outputs');

        $recipe->save();

        return sfView::HEADER_ONLY;
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

    public function executeBuildingUpdate(sfWebRequest $request) {
        $value = $request->getParameter('value');
        $this->forward404Unless($value);

        $data = json_decode($value, true);

        $record = Doctrine::getTable('Building')->findOneBy('type', $data['type']);
        if (!$record) {
            $record = new Building();
            $record->type = $data['type'];
        }

        $record->size_x = $data['size'][0];
        $record->size_y = $data['size'][1];

        $record->levels->fromArray($data['levels']);

        $record->save();

        return sfView::HEADER_ONLY;
    }

    public function executeStoreUpdate(sfWebRequest $request) {
        $id = $request->getParameter('id');
        $this->forward404Unless($id);

        $record = Doctrine::getTable('Store')->find($id);
        if (!$record) {
            $record = new Store();
            $record->id = $id;
        }

        $record->item_id = $request->getParameter('item_id');
        $record->price = $request->getParameter('price');
        $record->featured_until = $request->getParameter('featured_until');
        $record->sale = $request->getParameter('sale');
        $record->purchasable = $request->getParameter('purchasable') === 'true';
        $record->usable = $request->getParameter('usable') === 'true';
        $record->priority_id = $request->getParameter('priority_id');

        $record->save();

        return sfView::HEADER_ONLY;
    }

    public function executeFile() {
        $filename = sfConfig::get('sf_upload_dir') . '/-api-manifest.amf';
        $f = file_get_contents($filename);
        $this->getResponse()->setContentType('application/json');
        return $this->renderText($f);
    }

}
