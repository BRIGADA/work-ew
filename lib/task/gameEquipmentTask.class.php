<?php

class gameEquipmentTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'JSON-file from /api/manifest/equipment'),
    ));

    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'game';
    $this->name             = 'equipment';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:equipment|INFO] task does things.
Call it with:

  [php symfony game:equipment|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    
    $data = json_decode(file_get_contents($arguments['file']));
    foreach($data->response->equipment as $e)
    {
    	$this->logBlock($e->type, 'INFO');
    	$record_e = Doctrine::getTable('Equipment')->findOneBy('type', $e->type);
    	if(!$record_e) {
    		$record_e = new Equipment();
    		$record_e->type = $e->type;
    		$record_e->save();
    	}
    	
    	$levels = Doctrine::getTable('EquipmentLevel')
    		->createQuery('INDEXBY level')
    		->where('equipment_id = ?', $record_e->id)
    		->orderBy('level')
    		->execute();
    	
    	foreach ($e->levels as $l)
    	{
    		$record_l = $levels[$l->level];
    		$record_l->level = $l->level;
    		$record_l->equipment_id = $record_e->id;
    		$record_l->tier = $l->tier;
    		$record_l->time = $l->time;
    		$record_l->upgrade_chance = $l->upgrade_chance;
    		$record_l->require_g = $l->requirements->resources->gas;
    		$record_l->require_e = $l->requirements->resources->energy;
    		$record_l->require_u = $l->requirements->resources->uranium;
    		$record_l->require_c = $l->requirements->resources->crystal;
    		$record_l->require_s = $l->requirements->sp;
    		$record_l->stat_hp = $l->stats->hp;
    		$record_l->stat_range = $l->stats->range;    		
    		$record_l->stat_rate = $l->stats->attack_rate;    		
    		$record_l->stat_damage = $l->stats->damage;
    		$record_l->stat_targets = $l->stats->simultaneous_targets;    		
    		$record_l->stat_splash = $l->stats->splash_radius;
    		$record_l->stat_concussion = $l->stats->concussion_effect;
    	  $record_l->stat_defense = $l->stats->defense_exploder;
    	  
   	  	$record_l->tags = count($l->tags) ? $l->tags[0] : NULL;
    	}
    	
    	$levels->save();
    }
  }
}
