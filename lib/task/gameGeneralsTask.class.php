<?php

class gameGeneralsTask extends sfBaseTask
{
  protected function configure()
  {
		// add your own arguments here
		$this->addArguments ( array (
				new sfCommandArgument ( 'file', sfCommandArgument::REQUIRED, 'JSON file with generals' ) 
		) );

    $this->addOptions(array(
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'game';
    $this->name             = 'generals';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:generals|INFO] task does things.
Call it with:

  [php symfony game:generals|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

  	$data = json_decode ( file_get_contents ( $arguments ['file'] ), true);
  	
		foreach ( $data as $row ) {
			
			$record = Doctrine::getTable ( 'General' )->findOneBy('type', $row['type'] );
			if (! $record) {
				$record = new General();
				$record->type = $row['type'];
				$record->save();
				$this->logBlock($row['type'], 'INFO');
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
}
