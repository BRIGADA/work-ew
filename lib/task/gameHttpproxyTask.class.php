<?php

class gameHttpproxyTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
    // $this->addOptions(array(
    //   new sfCommandOption('my_option', null, sfCommandOption::PARAMETER_REQUIRED, 'My option'),
    // ));

    $this->namespace        = 'game';
    $this->name             = 'http-proxy';
    $this->briefDescription = 'Proxing HTTP-request to game server';
    $this->detailedDescription = <<<EOF
The [game:http-proxy|INFO] task does things.
Call it with:

  [php symfony game:http-proxy|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
  	$socket_file = '/tmp/edgeworld-http.sock';
  	$socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
  	if(!$socket) {
  		$this->syserror('socket_create');
  	}
  	$this->logBlock('Socket created', 'INFO');
  	
  	if(file_exists($socket_file)) {
  		if(!unlink($socket_file)) {
  			$this->syserror('unlink');
  		}
  	}
  	
  	if(!socket_bind($socket, $socket_file))
  	{
  		$this->syserror('socket_bind');
  	}
  	
  	chmod($socket_file, 0777);
  	
  	$this->logBlock('Socket binded', 'INFO');
  	
  	if(!socket_listen($socket)) {
  		$this->syserror('socket_listen');
  	}
  	
  	$this->logBlock('Socket listening', 'INFO');
  	
  	$r_socks = array();
  	$r_socks[] = $socket;
  	$w_socks = NULL;
  	$e_socks = NULL;
  	 
  	$this->logBlock('MAIN LOOP', 'INFO');
  	
  	$curls = array();
  	while(socket_select($r_socks, $w_socks, $e_socks, NULL) > 0)
  	{
  		if(in_array($socket, $r_socks)) {
  			$t1 = microtime(true);
  			//$this->logBlock('New connection', 'INFO');
  			$rsocket = socket_accept($socket);
  			
  			if(!$rsocket) {
  				$this->syserror('socket_accept');
  			}
  			
  			$length = intval(socket_read($rsocket, 100, PHP_NORMAL_READ));
//  			$this->logSection('read', sprintf('length = %u', $length));
  			$data = unserialize(socket_read($rsocket, $length));
//  			$this->logSection('read', sprintf('data = %s', $data));

				switch(strtolower($data['cmd'])) {
					case 'get':
						$this->logSection('GET', $data['host'].$data['path']);
						
						if(!isset($curls[$data['host']])) {
							$ch = curl_init();
							$curls[$data['host']] = $ch;
							curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
							curl_setopt($ch, CURLOPT_TIMEOUT, 10);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//							curl_setopt($ch, CURLOPT_VERBOSE, true);
							curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
							curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:20.0) Gecko/20100101 Firefox/20.0');								
						}
						else {
							$ch = $curls[$data['host']];
						}
						
						$query_str = http_build_query($data['query']);
						
						$url = sprintf('http://%s%s?%s', $data['host'], $data['path'], $query_str);

						curl_setopt($ch, CURLOPT_HTTPGET, true);
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: keep-alive'));
						curl_setopt($ch, CURLOPT_COOKIEFILE, sfConfig::get('sf_upload_dir').'/cookie/'.$data['user_id'].'.dat');
						curl_setopt($ch, CURLOPT_COOKIEJAR, sfConfig::get('sf_upload_dir').'/cookie/'.$data['user_id'].'.dat');						
						
						$result = curl_exec($ch);
						
						if(curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
						{
							$result = NULL;
						}
						
						$result = serialize($result);
						socket_write($rsocket, strlen($result)."\n".$result);
						break;
					case 'post':
						$this->logSection('POST', $data['host'].$data['path']);

						if(!isset($curls[$data['host']])) {
							$ch = curl_init();
							$curls[$data['host']] = $ch;
							curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
							curl_setopt($ch, CURLOPT_TIMEOUT, 10);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//							curl_setopt($ch, CURLOPT_VERBOSE, true);
							curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
							curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:20.0) Gecko/20100101 Firefox/20.0');
						}
						else {
							$ch = $curls[$data['host']];
						}						
												
						$postdata = str_replace('_', '%5F', http_build_query($data['query']));
						
						$url = sprintf('http://%s%s', $data['host'], $data['path']);
						
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
						curl_setopt($ch, CURLOPT_COOKIEFILE, sfConfig::get('sf_upload_dir').'/cookie/'.$data['user_id'].'.dat');
						curl_setopt($ch, CURLOPT_COOKIEJAR, sfConfig::get('sf_upload_dir').'/cookie/'.$data['user_id'].'.dat');
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-s3-cachebreak: '.sha1($url.$postdata.'SF'.'f0uR'.'l1f3'), 'Connection: keep-alive'));
						
						$result = curl_exec($ch);
						
						if(curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
						{
							$result = NULL;
						}
						
//						$this->log(var_export(curl_getinfo($ch), true));
//						$this->log(var_export($result, true));
						
						$result = serialize($result);
						socket_write($rsocket, strlen($result)."\n".$result);
						
						break;
					default:
						$this->logBlock(sprintf('Unknown command \'%s\'', $data['cmd']), 'ERROR');
				} 			

  			socket_close($rsocket);
  			
  			$t2 = microtime(true);

  			$this->logSection('duration', round($t2 - $t1, 3));
  		}
  	}
  }
  
  protected function syserror($function)
  {
  	$this->logBlock($function, 'ERROR');
 		die();
  }
}
