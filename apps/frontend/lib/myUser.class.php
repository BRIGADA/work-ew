<?php

class myUser extends sfBasicSecurityUser
{
	
	public function RGET($path, $query = array())
	{		
		$query['meltdown'] = MeltdownTable::getLast();
		$query['reactor'] = $this->getAttribute('reactor', null, 'playerVO');
		$query['user_id'] = $this->getAttribute('user_id', null, 'playerVO');
		$query['_session_id'] = $this->getAttribute('_session_id', null, 'playerVO');
		
		sfContext::getInstance()->getLogger()->debug('RGET-query: '.var_export($query, true));
		
		$query_str = http_build_query($query);
		
		$url = sprintf('http://%s%s?%s', $this->getAttribute('host', null, 'playerVO'), $path, $query_str);
		sfContext::getInstance()->getLogger()->debug('RGET-url: '.$url);
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:20.0) Gecko/20100101 Firefox/20.0');
		
		$cookie_file = sfConfig::get('sf_upload_dir').'/cookie/'.$this->getAttribute('user_id', 'unknown', 'client').'.dat';
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
				
		$result = curl_exec($ch);
		sfContext::getInstance()->getLogger()->debug('RGET-result: '.var_export($result, true));
		
		sfContext::getInstance()->getLogger()->debug('RGET-info: '.var_export(curl_getinfo($ch), true));
		
		if(curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
		{
			$result = NULL;
		}
		
		curl_close($ch);
		
		return $result;
	}
	
	public function RPOST($path, $query = array())
	{
		$query['meltdown'] = MeltdownTable::getLast();
		$query['reactor'] = $this->getAttribute('reactor', null, 'playerVO');
		$query['user_id'] = $this->getAttribute('user_id', null, 'playerVO');
		$query['_session_id'] = $this->getAttribute('_session_id', null, 'playerVO');
		$query['testCount'] = $this->getAttribute('testCount', 1, 'client');
		
		sfContext::getInstance()->getLogger()->debug('query: '.var_export($query, true));

		$postdata = str_replace('_', '%5F', http_build_query($query));

		$url = sprintf('http://%s%s', $this->getAttribute('host', null, 'playerVO'), $path);
		
		$ch = curl_init($url);
		
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:20.0) Gecko/20100101 Firefox/20.0');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		
		$cookie_file = sfConfig::get('sf_upload_dir').'/cookie/'.$this->getAttribute('user_id', 'unknown', 'client').'.dat';
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		
		$hashsting = $url.$postdata.'SF'.'f0uR'.'l1f3';
		
		$x_s3_cachebreak = sha1($hashsting);
		
		sfContext::getInstance()->getLogger()->debug(sprintf('hash for: \'%s\' = %s', $hashsting, $x_s3_cachebreak));

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-s3-cachebreak: '.$x_s3_cachebreak));
		
		$result = curl_exec($ch);
		
//		sfContext::getInstance()->getLogger()->debug(var_export(curl_getinfo($ch), true));

		if(curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
		{
			$result = NULL;
		}
 		else
 		{
 			$this->setAttribute('testCount', $this->getAttribute('testCount', 1, 'playerVO') + 1, 'playerVO');
 		}
		
		curl_close($ch);
		return $result;
	}
	
	public function ProxyGET($path, $query = array())
	{
		$host = $this->getAttribute('host', null, 'playerVO');
		$user_id = $this->getAttribute('user_id', null, 'playerVO');
		
		$query['meltdown'] = MeltdownTable::getLast();
		$query['reactor'] = $this->getAttribute('reactor', null, 'playerVO');
		$query['user_id'] = $user_id;
		$query['_session_id'] = $this->getAttribute('_session_id', null, 'playerVO');
		
		return $this->proxy('get', array('host'=>$host, 'path' => $path, 'query'=>$query, 'user_id'=>$user_id));
	}
	
	public function ProxyPOST($path, $query = array())
	{
		$host = $this->getAttribute('host', null, 'playerVO');
		$user_id = $this->getAttribute('user_id', null, 'playerVO');
		
		$query['meltdown'] = MeltdownTable::getLast();
		$query['reactor'] = $this->getAttribute('reactor', null, 'playerVO');
		$query['user_id'] = $this->getAttribute('user_id', null, 'playerVO');
		$query['_session_id'] = $this->getAttribute('_session_id', null, 'playerVO');
		$query['testCount'] = $this->getAttribute('testCount', 1, 'playerVO');
		
		$response = $this->proxy('post', array('host'=>$host, 'path' => $path, 'query'=>$query, 'user_id'=>$user_id));
		
		if($response !== NULL) {
			$this->setAttribute('testCount', $this->getAttribute('testCount', 1, 'client') + 1, 'playerVO');
		}
		return $response;
	}
	
	public function proxy($command, $data)
	{
		$data['cmd'] = $command;
	
		$s = socket_create(AF_UNIX, SOCK_STREAM, 0);
		$f = serialize($data);
		if(!socket_connect($s, '/tmp/edgeworld-http.sock')) {
			die('socket_connect');
		}
		$w = socket_write($s, strlen($f)."\n".$f);
		$r = socket_read($s, 10, PHP_NORMAL_READ);
		$l = intval($r);
		$r = unserialize(socket_read($s, $l));
		socket_close($s);
		return $r;
	}
}
