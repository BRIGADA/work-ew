<?php
class gameItemsTask extends sfBaseTask {
	protected function configure() {
		// add your own arguments here
		$this->addArguments ( array (
				new sfCommandArgument ( 'file', sfCommandArgument::REQUIRED, 'JSON file with items' ) 
		) );
		
		$this->addOptions ( array (
				new sfCommandOption ( 'connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine' ) 
		// add your own options here
				) );
		
		$this->namespace = 'game';
		$this->name = 'items';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
The [game:items|INFO] task does things.
Call it with:

  [php symfony game:items|INFO]
EOF;
	}
	protected function execute($arguments = array(), $options = array()) {
		// initialize the database connection
		$databaseManager = new sfDatabaseManager ( $this->configuration );
		$connection = $databaseManager->getDatabase ( $options ['connection'] )->getConnection ();
		
		$data = json_decode ( file_get_contents ( $arguments ['file'] ) );
		foreach ( $data as $item ) {
			$record = Doctrine::getTable ( 'Item' )->find ( $item->id );
			if (! $record) {
				$record = new Item ();
				$record->id = $item->id;
			}
			
			$record->type = $item->type;
			
			$record->permanent = $item->permanent;
			
			$record->tags = $item->tags;
			
			foreach ( array ('contents', 'boost_amount', 'boost_type', 'boost_percentage', 'resource_amount', 'resource_type') as $field ) {
				if(isset($item->$field)) {
					if($item->$field !== $record->$field) {
						$record->$field = $item->$field;
					}
				}
				else {
					$record->$field = NULL;
				}
			}
			
			if ($record->isModified ()) {
				$this->logBlock ( $item->type, 'INFO' );
				$record->save ();
			}
		}
	}
}
