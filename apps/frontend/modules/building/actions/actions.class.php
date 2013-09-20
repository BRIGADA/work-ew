<?php

/**
 * building actions.
 *
 * @package    edgeworld
 * @subpackage building
 * @author     BRIGADA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class buildingActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->buildings = Doctrine_Core::getTable('building')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->building = Doctrine_Core::getTable('building')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->building);
    
    $this->levels = Doctrine::getTable('BuildingLevel')
    	->createQuery()
    	->where('building_id = ?', $this->building->id)
    	->orderBy('level')
    	->fetchArray();
    
    $this->requirements = array();
    $this->stats = array();
    foreach($this->levels as $level)
    {
    	foreach($level['requirements'] as $k => $v) 
    	{
    		if(!in_array($k, $this->requirements)) {
    			$this->requirements[] = $k;
    		}    		
    	}
    	foreach($level['stats'] as $k => $v)
    	{
    		if(!in_array($k, $this->stats)) {
    			$this->stats[] = $k;
    		}
    	}    	 
    }
  
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new buildingForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new buildingForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($building = Doctrine_Core::getTable('building')->find(array($request->getParameter('id'))), sprintf('Object building does not exist (%s).', $request->getParameter('id')));
    $this->form = new buildingForm($building);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($building = Doctrine_Core::getTable('building')->find(array($request->getParameter('id'))), sprintf('Object building does not exist (%s).', $request->getParameter('id')));
    $this->form = new buildingForm($building);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($building = Doctrine_Core::getTable('building')->find(array($request->getParameter('id'))), sprintf('Object building does not exist (%s).', $request->getParameter('id')));
    $building->delete();

    $this->redirect('building/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $building = $form->save();

      $this->redirect('building/edit?id='.$building->getId());
    }
  }
}
