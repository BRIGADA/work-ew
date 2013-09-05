<?php

class gameManifestTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'Manifest'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'game';
    $this->name             = 'manifest';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:manifest|INFO] task does things.
Call it with:

  [php symfony game:manifest|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $this->configuration->registerZend();

    $this->logSection('file', 'Loading...');
    $content = file_get_contents($arguments['file']);
    $istream = new Zend_Amf_Parse_InputStream($content);
    $parser = new Zend_Amf_Parse_Amf3_Deserializer($istream);
    $this->logSection('file', 'Parsing...');    
    $r = $parser->readTypeMarker();
    $this->logSection('file', 'done!');
    
    foreach($r as $k => $v)
    {
    	$m = "update_{$k}";
    	if(method_exists($this, $m)) {
    		$this->$m($v);
    	}
    	else {
    		file_put_contents("manifest-{$k}.json", json_encode($v));
    	}
    }
  }
  
  protected function updat1e_buildings(&$data)
  {
  	$b = array();
  	foreach($data as $row)
  	{
  		$b[] = $row->type;
  		$record = Doctrine::getTable('Building')->findOneBy('type', $row->type);
  		if(!$record) {
  			$this->logSection('building', sprintf('add \'%s\'',$row->type));
  			$record = new Building();
  			$record->type = $row->type;
  		}
  		
  		$l = array();
  		
  		foreach($row->levels as $dl)
  		{
  			$l[] = $dl->level;
  			$found = false;
  			foreach($record->levels as $rl)
  			{
  				if($dl->level == $rl->level)
  				{
  					$found = true;
  					break;
  				}
  			}
  			
  			if(!$found) {
  				$rl = $record->levels->get(NULL);
  				$rl->level = $dl->level;  				
  			}
  			
  			$rl->requirements = json_encode($dl->requirements);
  			$rl->stats = json_encode($dl->stats);
  		}
  		
  		$record->save();
  		/*
  		Doctrine::getTable('BuildingLevel')
  			->createQuery()
  			->delete()
  			->where('building_id = ?', $record->id)
  			->andWhereNotIn('level', $l)
  			->execute();
  			*/
  	}
  	Doctrine::getTable('Building')
  		->createQuery()
  		->delete()
  		->whereNotIn('type', $b)
  		->execute();
  }
  
  protected function update_defense($data)
  {
  	$data = json_decode(json_encode($data), true);
  	foreach ( $data as $row ) {
  			
  		$record = Doctrine::getTable ( 'Defense' )
  		->createQuery('a')
  		->leftJoin('a.levels b INDEXBY b.level')
  		->where('a.type = ?', $row['type'])
  		->fetchOne();
  			
  		if (! $record) {
  			$record = new Defense();
  			$record->type = $row['type'];
  			//				$record->save();
  		}
  			
  		foreach ($row['levels'] as $l)
  		{
  			$record->levels[$l['level']]->fromArray($l);
  		}
  			
  		if($record->isModified(true)) {
  			$this->logSection('defense', $row['type']);
  		}
  			
  		$record->save();
  	}
  }
  
  protected function update_research($data)
  {
  	$data = json_decode(json_encode($data), true);
  	 
  	foreach ( $data as $row ) {
  			
  		$record = Doctrine::getTable ( 'Research' )->findOneBy('type', $row['type'] );
  		if (! $record) {
  			$record = new Research();
  			$record->type = $row['type'];
  			$record->save();
  			$this->logSection('research',$row['type']);
  		}
  			
  		$levels = Doctrine::getTable('ResearchLevel')
  		->createQuery('INDEXBY level')
  		->where('research_id = ?', $record->id)
  		->orderBy('level')
  		->execute();
  			
  		foreach ($row['levels'] as $l)
  		{
  			$levels[$l['level']]->fromArray($l);
  			$levels[$l['level']]->research_id = $record->id;
  		}
  			
  		$levels->save();
  	}  	 
  }
  
  protected function update_generals($data)
  {
  	$data = json_decode(json_encode($data), true);
  	  	 
  	foreach ( $data as $row ) {
  			
  		$record = Doctrine::getTable ( 'General' )->findOneBy('type', $row['type'] );
  		if (! $record) {
  			$record = new General();
  			$record->type = $row['type'];
  			$record->save();
  			$this->logSection('general', $row['type']);
  		}
  			
  		$levels = Doctrine::getTable('GeneralLevel')
  		->createQuery('INDEXBY level')
  		->where('general_id = ?', $record->id)
  		//				->orderBy('level')
  		->execute();
  			
  		foreach ($row['levels'] as $l)
  		{
  			$levels[$l['level']]->fromArray($l);
  			$levels[$l['level']]->general_id = $record->id;
  		}
  			
  		$levels->save();
  	}
  	 
  }
  
  protected function update_skills($data)
  {
  	$data = json_decode(json_encode($data), true);
  	
  	foreach ( $data as $row ) {
  			
  		$record = Doctrine::getTable ( 'Skill' )->findOneBy('type', $row['type'] );
  		if (! $record) {
  			$record = new Skill();
  			$record->type = $row['type'];
  			$record->save();
  			$this->logSection('skill', $row['type']);
  		}
  			
  		$levels = Doctrine::getTable('SkillLevel')
  		->createQuery('INDEXBY level')
  		->where('skill_id = ?', $record->id)
  		//				->orderBy('level')
  		->execute();
  			
  		foreach ($row['levels'] as $l)
  		{
  			$levels[$l['level']]->fromArray($l);
  			$levels[$l['level']]->skill_id = $record->id;
  		}
  			
  		$levels->save();
  	}  	 
  }
  
  protected function update_units($data)
  {
  	$data = json_decode(json_encode($data), true);
  	 
  	foreach ( $data as $row ) {
  			
  		$record = Doctrine::getTable ( 'Unit' )->findOneBy('type', $row['type'] );
  		if (! $record) {
  			$record = new Unit();
  			$record->type = $row['type'];
  			$record->save();
  			$this->logSection('units', $row['type']);
  		}
  			
  		$levels = Doctrine::getTable('UnitLevel')
	  		->createQuery('INDEXBY level')
	  		->where('unit_id = ?', $record->id)
	  		->execute();
  			
  		foreach ($row['levels'] as $l)
  		{
  			$levels[$l['level']]->fromArray($l);
  			$levels[$l['level']]->unit_id = $record->id;
  		}
  			
  		$levels->save();
  	}
  }
  
  protected function update_items($data)
  {
  	$data = json_decode(json_encode($data), true);
 		foreach ( $data as $row )
 		{
			$record = Doctrine::getTable ( 'Item' )->find ( $row['id'] );
			if (! $record) {
				$record = new Item ();
				$record->id = $row['id'];
			}
			
			$record->type = $row['type'];
			
			$record->permanent = $row['permanent'];
			
			$record->tags = $row['tags'];
			
			foreach ( array ('contents', 'boost_amount', 'boost_type', 'boost_percentage', 'resource_amount', 'resource_type') as $field ) {
				if(isset($row[$field])) {
					if($row[$field] !== $record->$field) {
						$record->$field = $row[$field];
					}
				}
				else {
					$record->$field = NULL;
				}
			}
			
			if ($record->isModified ()) {
				$this->logSection('items', $row['type'] );
				$record->save ();
			}
		}
  }
}
