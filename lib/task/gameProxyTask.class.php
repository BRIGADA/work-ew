<?php

class gameProxyTask extends sfBaseTask
{
  protected $sock = NULL;
  protected $in = '';
  protected $out = array();
  protected $remote_addr = 'sector181.c1.galactic.wonderhill.com';
  protected $remote_port = 8000;

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
    
    //{\"type\":\"subscribe\",\"data\":{\"player_id\":\"1974957\"}}\x0d\x0a
		//{\"type\":\"chat_join\",\"data\":{\"player_id\":\"1974957\",\"room\":\"global::181\"}}\x0d\x0a
		//{\"type\":\"chat_join\",\"data\":{\"player_id\":\"1974957\",\"room\":\"alliance::444136::181\"}}\x0d\x0a
		//{\"type\":\"chat_join\",\"data\":{\"player_id\":\"1974957\",\"room\":\"locale::181::en\",\"player_name\":\"BRIGADA\"}}\x0d\x0a
    
    $this->sector = 181;
    $this->player_id = 1974957;
    $this->player_name = "BRIGADA";
    $this->alliance_id = 444136;
    
//    $this->out[] = 
    
    
    $this->connect();
    
    while(true) {
    	$rsocks = array($this->sock);
    	$wsocks = count($this->out) ? array($this->sock) : array();
    	$esocks = null;
    	
    	if(socket_select($rsocks, $wsocks, $esocks, null) < 0)
    	{
    		die('socket_select');
    	}
    	
    	if(in_array($this->sock, $rsocks)) {
    		$r = socket_read($this->sock, 4096);
    		if(strlen($r) == 0) {
    			$this->logBlock('RECONECTING', 'ERROR');
    			socket_shutdown($this->sock);
					socket_close($this->sock);
					$this->out = array();
    			$this->connect();
    		}
    		else {
	    		$messages = explode("\r\n", $this->in.$r);
	    		while(count($messages) > 1) {
	    			$cur = array_shift($messages);
	    			
	    			$c = json_decode($cur, true);
	    			
	    			$record = new Proxy();
	    			$record->type = $c['type'];
	    			$record->params = $c['data'];
	    			$record->save();
	    			
	    			switch($c['type']) {
	    				case 'subscribe':
						    $this->out[] = array('type'=>'chat_join', 'data'=>array('player_id'=>strval($this->player_id), 'room'=>sprintf('global::%u', $this->sector)));
						    $this->out[] = array('type'=>'chat_join', 'data'=>array('player_id'=>strval($this->player_id), 'room'=>sprintf('alliance::%u::%u', $this->alliance_id, $this->sector)));
						    $this->out[] = array('type'=>'chat_join', 'data'=>array('player_id'=>strval($this->player_id), 'room'=>sprintf('locale::%u::en', $this->sector), 'player_name'=>$this->player_name));
	    					break;
	    					
	    				case 'chat_message':
	    					$chat = new Chat();
	    					$chat->fromArray($c['data']);
	    					$chat->save();	    					
	    					break;

	    				case 'chat_join':
	    					break;
	    					
	    				default:
	    					$this->logSection('>>>>>', $cur);
	    				case 'job_completed':
	    				case 'xp_changed':
	    				case 'level_changed':
	    				case 'force_changed':
	    				case 'player_boosts_changed':
	    				case 'energies_available_changed':
	    					
//	    					$record->d 
								break;
	    			}    			
	    		}
	    		$this->in = implode("\r\n", $messages);
    		}
    	}
    	
    	if(in_array($this->sock, $wsocks)) {
    		if(count($this->out)) {
    			$cur = json_encode(array_shift($this->out));
    			$this->logSection('<<<', $cur);
    			socket_write($this->sock, $cur."\r\n");
    		}
    	}
    }

    socket_close($this->sock);
  }
  
  protected function connect()
  {
    $this->sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    
    if(!$this->sock) {
    	$this->SOCKERROR('create');
    	return;
    }
  	if(!socket_connect($this->sock, $this->remote_addr, $this->remote_port)) {
  		$this->SOCKERROR('connect');
  		die('socket_connect');
  	}
  	
    $this->out = array();
  	$this->out[] = array('type'=>'subscribe', 'data'=>array('player_id'=>strval($this->player_id)));  	
  }

  protected function RECV(&$itype, &$idata)
  {
  	while(true)
  	{
  		$r = array($this->sock);
  		$w = array($this->sock);
  		 
  		switch(socket_select($r, $w, $e, NULL))
  		{
  			case FALSE:
  				$this->SOCKERROR('select', $this->sock);
  				return false;
  			case 0:
  				$this->logSection('select', 'timeout');
  				return false;
  			default:
  				var_dump($r, $w);
  				
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
