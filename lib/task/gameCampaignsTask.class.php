<?php

class gameCampaignsTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'JSON-data'),
    ));

    $this->addOptions(array(
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'game';
    $this->name             = 'campaigns';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:campaigns|INFO] task does things.
Call it with:

  [php symfony game:campaigns|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $file = json_decode(file_get_contents($arguments['file']));
    
    foreach($file->campaigns as $row) {
    	$this->logSection('campaign', $row->name);
    	
    	$campaign = Doctrine::getTable('Campaign')->findOneBy('id', $row->id);
    	if(!$campaign) {
    		$campaign = new Campaign();
    		$campaign->id = $row->id;
    	}
    	
    	$campaign->name = $row->name;    	
    	$campaign->unlock_level = $row->unlock_level;
    	
    	foreach($row->stages as $i => $stage) {
    		$this->logSection('stage', $stage->name);
    		foreach(array('id', 'name', 'player_unlock_level', 'attacker_boost', 'attacker_level', 'unit_level', 'player_unlock_level', 'baseline_xp') as $field) {
    			$campaign->stages[$i]->$field = $stage->$field;
    		}
    		foreach($stage->units as $j => $unit) {
    			foreach (array('type', 'quantity', 'x', 'y', 'time') as $field) {
	    			$campaign->stages[$i]->units[$j]->$field = $unit->$field;
    			}
    		}
    	}
    	
    	$campaign->save();
    	
    }

    
    
  }
}
