<?php

class gameDefenseTask extends sfBaseTask
{
  protected function configure()
  {
		// add your own arguments here
		$this->addArguments ( array (
				new sfCommandArgument ( 'file', sfCommandArgument::REQUIRED, 'JSON file with defense' ) 
		) );

    $this->addOptions(array(
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));
  	
  	
    $this->namespace        = 'game';
    $this->name             = 'defense';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:defense|INFO] task does things.
Call it with:

  [php symfony game:defense|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

   	$data = json_decode ( file_get_contents ( $arguments ['file'] ), true);
  	
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
}
