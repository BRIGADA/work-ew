<?php

class gameConvertchatTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'game';
    $this->name             = 'convert-chat';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:convert-chat|INFO] task does things.
Call it with:

  [php symfony game:convert-chat|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $messages = Doctrine::getTable('Proxy')
    	->createQuery()
    	->where('type = ?', 'chat_message')
    	->orderBy('created_at, id')
    	->execute(null, Doctrine::HYDRATE_ON_DEMAND);
    
    foreach ($messages as $k => $msg)
    {
    	$record = new Chat();
//    	var_dump($msg->params);
//    	$record->player_id = $msg->params['player_id'];
//     	$record->message = $msg->params['message'];
//     	$record->room = $msg->params['room'];
//     	$record->user_card = $msg->params['user_card'];
//    	$record->player_id = 
    	$record->fromArray($msg->params);
    	$record->created_at = $msg->created_at;
    	$record->save();
    	$msg->delete();
    	
    	if(($k + 1) % 10 == 0) {
    		$this->logSection('processed', $k + 1);
    	}
    }
  }
}
