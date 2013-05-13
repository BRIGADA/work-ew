<?php

class gameProxyTask extends sfBaseTask
{
  protected $sock = NULL;
  protected $buffer = '';

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
    $this->name             = 'proxy';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:proxy|INFO] task does things.
Call it with:

  [php symfony game:proxy|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
//    $remote_addr = "192.168.1.2";
//    $remote_port = 8001;
    $remote_addr = "c1.galactic.wonderhill.com";
    $remote_port = 8000;
    $player_id = strval(1979406);
    $player_name = "stones";
    $alliance_id = 433853;
    

    $sector = 181;
    
    $this->sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    
    if(!$this->sock) {
    	$this->SOCKERROR('create');
    	return;
    }

    if(!socket_connect($this->sock, $remote_addr, $remote_port)) {
    	$this->SOCKERROR('connect');
    	return;
    }
    
    $this->SEND('subscribe', array('player_id'=>$player_id));
    
    if($this->RECV($itype, $idata))
    {
    	$this->logSection('type', $itype);
    	$this->logSection('data', json_encode($idata));
    	
    	$this->SEND('chat_join', array('player_id'=>$player_id, 'room'=>sprintf('global::%d', $sector)));
	$this->SEND('chat_join', array('player_id'=>$player_id, 'room'=>sprintf('alliance::%d::%d', $alliance_id, $sector)));
	$this->SEND('chat_join', array('player_id'=>$player_id, 'player_name'=>$player_name, 'room'=>sprintf('locale::%d::en', $sector)));
    	while($this->RECV($itype, $idata))
    	{
	    $record = new Proxy();
            $record->type = $itype;
	    $record->params = serialize($idata);
	    $record->save();

	    switch($itype)
	    {
		case 'chat_message':
		    
		    if($idata->user_card->alliance)
		    {
			$this->logSection($idata->room, sprintf('%s [%s]: %s', $idata->user_card->name, $idata->user_card->alliance->name, $idata->message));
		    }
		    else
		    {
			$this->logSection($idata->room, sprintf('%s: %s', $idata->user_card->name, $idata->message));
		    }
		    break;
	    }
    		
    	}
    }
    
    socket_close($this->sock);
  }
  
  protected function RECV(&$itype, &$idata)
  {
  	while(true)
  	{
  		$r = array($this->sock);
  		 
  		switch(socket_select($r, $w, $e, NULL))
  		{
  			case FALSE:
  				$this->SOCKERROR('select', $this->sock);
  				return false;
  			case 0:
  				$this->logSection('select', 'timeout');
  				return false;
  			default:
  				$buf = socket_read($this->sock, 100000);
  				if(strlen($buf)) {
  					$this->logSection('<<<', trim($buf));
  					$this->buffer .= $buf;
  	
  					$p = strpos($this->buffer, "\r\n");
  					if($p !== FALSE) {
  						$msg = json_decode(substr($this->buffer, 0, $p));
  						$this->buffer = substr($this->buffer, $p + 2);
  						
  						$itype = $msg->type;
  						$idata = $msg->data;
  						return true;
  					}
  					 
  				}
  				else {
  					// connection closed
  					$this->logSection('connection', 'remote closed');
  					return FALSE;
  				}
  		}
  	}
  }

  protected function SEND($type, $data)
  {
  	$msg = array();
  	$msg['data'] = $data;
  	$msg['type'] = $type;
  	
    $out = json_encode($msg)."\r\n";
    $this->logSection('>>>', trim($out));    
    do {
    	$l = socket_write($this->sock, $out);
    	if($l === false) {
    		$this->SOCKERROR('write', $this->sock);
    		return false;
    	}    	
    	$out = substr($out, $l);
    } while (strlen($out));
    return true;
  }
  
  protected function SOCKERROR($fn, $sock = NULL)
  {
  	$errno = $sock === NULL ? socket_last_error() : socket_last_error($sock);
  	$this->logBlock(sprintf('%s: %s (%d)', $fn, socket_strerror($errno), $errno), 'ERROR');
  }
}
