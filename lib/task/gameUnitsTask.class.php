<?php

class gameUnitsTask extends sfBaseTask
{
  protected function configure()
  {
		// add your own arguments here
		$this->addArguments ( array (
				new sfCommandArgument ( 'file', sfCommandArgument::REQUIRED, 'JSON file with items' ) 
		) );

    $this->addOptions(array(
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'game';
    $this->name             = 'units';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:units|INFO] task does things.
Call it with:

  [php symfony game:units|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

  	$data = json_decode ( file_get_contents ( $arguments ['file'] ), true);
  	
		foreach ( $data as $row ) {
			
			$record = Doctrine::getTable ( 'Unit' )->findOneBy('type', $row['type'] );
			if (! $record) {
				$record = new Unit();
				$record->type = $row['type'];
				$record->save();
				$this->logBlock($row['type'], 'INFO');
			}
			
			$levels = Doctrine::getTable('UnitLevel')
				->createQuery('INDEXBY level')
				->where('unit_id = ?', $record->id)
//				->orderBy('level')
				->execute();
			
			foreach ($row['levels'] as $l)
			{
				$levels[$l['level']]->fromArray($l);
				$levels[$l['level']]->unit_id = $record->id;
			}
			
			$levels->save();
		}
  }
}
