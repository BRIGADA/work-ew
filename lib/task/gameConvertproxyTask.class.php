<?php

class gameConvertproxyTask extends sfBaseTask
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
    $this->name             = 'convert-proxy';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:convert-proxy|INFO] task does things.
Call it with:

  [php symfony game:convert-proxy|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $records = Doctrine::getTable('Proxy')
    	->createQuery()
    	->where('type = ?', 'chat_message')
    	->orderBy('id')
    	->fetchArray();
   	foreach($records as $r)
   	{
   		$p = unserialize($r['params']);

   		$n = new Chat();
//   		$n->created_at = $r->created_at;
			$n->room = $p->room;
   		$n->message = $p->message;
   		$n->sender_id = $p->user_card->id;
   		$n->sender = $p->user_card->name;

   		$n->alliance_id = $p->user_card->alliance ? $p->user_card->alliance->id : NULL;
   		$n->alliance = $p->user_card->alliance ? $p->user_card->alliance->name : NULL;
   		
   		$n->save();
   		
   	}

    // add your code here
  }
}
