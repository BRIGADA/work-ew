<?php

class myUser extends sfBasicSecurityUser
{
	public function clientParams()
	{
		$result = array();
		
		foreach(array('meltdown', 'reactor', 'user_id', '_session_id') as $param) {
			$result[$param] = $this->getAttribute($param, null, 'client');
		}
		
		return $result;
	}
	
	public function RGET($path, $query = array())
	{		
		$query_str = http_build_query(array_merge($this->clientParams(), $query));
		
		$url = sprintf('http://%s%s?%s', $this->getAttribute('host', null, 'client'), $path, $query_str);
		 
		//  	$f = fopen(sfConfig::get('sf_log_dir').'/headers.txt', 'w+');
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:20.0) Gecko/20100101 Firefox/20.0');
		//  	curl_setopt($ch, CURLOPT_WRITEHEADER, $f);
		$result = curl_exec($ch);
		curl_close($ch);
		
		return $result;
	}
	
	public function RPOST($path, $query = array())
	{
		$postdata = str_replace('_', '%5F', http_build_query($query));

		$url = sprintf('http://%s%s', $this->getAttribute('host', null, 'client'), $path);
		
		$ch = curl_init('http://localhost');
		
		//  	$f = fopen(sfConfig::get('sf_log_dir').'/headers.txt', 'w+');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:20.0) Gecko/20100101 Firefox/20.0');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-s3-cachebreak: '.sha1($url.$postdata.'SF'.'f0uR'.'l1f3')));
		
		//  	curl_setopt($ch, CURLOPT_WRITEHEADER, $f);
		$result = curl_exec($ch);
		curl_close($ch);
		
		return $result;
		
		
	}
}
