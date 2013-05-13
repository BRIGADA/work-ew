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
  
  protected function update_buildings(&$data)
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
}
