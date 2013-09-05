<?php

class gameSkillsTask extends sfBaseTask
{
  protected function configure()
  {
		// add your own arguments here
		$this->addArguments ( array (
				new sfCommandArgument ( 'file', sfCommandArgument::REQUIRED, 'JSON file with skills' ) 
		) );

    $this->addOptions(array(
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'game';
    $this->name             = 'skills';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:skills|INFO] task does things.
Call it with:

  [php symfony game:skills|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

  	$data = json_decode ( file_get_contents ( $arguments ['file'] ), true);
  	
		foreach ( $data as $row ) {
			
			$record = Doctrine::getTable ( 'Skill' )->findOneBy('type', $row['type'] );
			if (! $record) {
				$record = new Skill();
				$record->type = $row['type'];
				$record->save();
				$this->logBlock($row['type'], 'INFO');
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
}
