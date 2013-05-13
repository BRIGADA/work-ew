<?php

class gameSrvTask extends sfBaseTask
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
    $this->name             = 'srv';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:srv|INFO] task does things.
Call it with:

  [php symfony game:srv|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    
    $address = '192.168.1.2';
    $port = 8001;
    
    if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    	$this->ERROR($sock, 'socket_create');
    }
    
    if (socket_bind($sock, $address, $port) === false) {
    	$this->ERROR($sock, 'socket_bind');
    }
    
    if (socket_listen($sock, 5) === false) {
    	$this->ERROR($sock, 'socket_listen');
    }
    
    $u = array();
    
    do {
    	$this->log('main loop');
    	$r = array();
    	$w = NULL;
    	$e = array();
    	
   		$r[] = $sock;
   		$e[] = $sock;
   		
    	foreach ($u as $usock)
    	{
    		$r[] = $usock;
    		$e[] = $usock;
    	}
    	
    	switch(socket_select($r, $w, $e, null))
    	{
    		case FALSE:
    			$this->ERROR(null, 'socket_select');
    			break 2;
    		case 0:
    			$this->log('timeout');
    			break;
    		default:
//    			var_dump($r, $e);
    			if(count($r))
    			{
	    			if(isset($r[0]))
	    			{
	    				$usock = socket_accept($r[0]);
	    				$u[] = $usock;
	    			}
	    			else 
	    			{
	    				foreach($r as $n => $usock)
	    				{
	    					$buf = '';
	    					$l = socket_recv($usock, $buf, 1000, 0);
	    					
	    					if($l == 0)
	    					{
	    						socket_close($usock);
	    						unset($u[$n-1]);
	    					}
	    					else 
	    					{
	    						$this->logSection('>>>>', sprintf('\'%s\'', $buf));	    							
	    					}
	    				}
	    			}
    			}
    			break;    			
    	}
    } while (true);
    
    socket_close($sock);
    
  }
  
  protected function ERROR($sock, $fn) {
  	$errno = socket_last_error($sock);
  	$msg = sprintf('%s - %s (%d)', $fn, socket_strerror($errno), $errno);
  	$this->logBlock($msg, 'ERROR');
  }
}
