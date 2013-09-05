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
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:20.0) Gecko/20100101 Firefox/20.0');
		//  	curl_setopt($ch, CURLOPT_WRITEHEADER, $f);
		
		$cookie_file = sfConfig::get('sf_upload_dir').'/cookie/'.$this->getAttribute('user_id', 'unknown', 'client').'.dat';
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
				
		$result = curl_exec($ch);
		
		sfContext::getInstance()->getLogger()->debug(var_export(curl_getinfo($ch), true));
		
		if(curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
		{
			$result = NULL;
		}
// 		else
// 		{
// 			$fn = sfConfig::get('sf_upload_dir').'/'.date('Y-m-d\TH:i:s').' GET '.str_replace('/', '-', $path);
// 			file_put_contents($fn, $result);
// 		}
		
		curl_close($ch);
		
		return $result;
	}
	
	public function RPOST($path, $query = array())
	{
		$query = array_merge($this->clientParams(), $query);
		$query['testCount'] = $this->getAttribute('testCount', 1, 'client');

		$postdata = str_replace('_', '%5F', http_build_query($query));

		$url = sprintf('http://%s%s', $this->getAttribute('host', null, 'client'), $path);
		
//		$ch = curl_init('http://edgeworld.local/frontend_dev.php');
		$ch = curl_init($url);
		
		//  	$f = fopen(sfConfig::get('sf_log_dir').'/headers.txt', 'w+');
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:20.0) Gecko/20100101 Firefox/20.0');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		
		$cookie_file = sfConfig::get('sf_upload_dir').'/cookie/'.$this->getAttribute('user_id', 'unknown', 'client').'.dat';
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-s3-cachebreak: '.sha1($url.$postdata.'SF'.'f0uR'.'l1f3')));
		
		$f = fopen(sfConfig::get('sf_upload_dir').'/headers.txt', 'w+');
		curl_setopt($ch, CURLOPT_WRITEHEADER, $f);
		
		$result = curl_exec($ch);
		
		if(curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
		{
			$result = NULL;
		}
 		else
 		{
// 			$fn = sfConfig::get('sf_upload_dir').'/'.date('Y-m-d\TH:i:s').' POST '.str_replace('/', '-', $path);
// 			file_put_contents($fn, $result);
 			$this->setAttribute('testCount', $this->getAttribute('testCount', 1, 'client') + 1, 'client');
 		}
		
		curl_close($ch);
		return $result;
	}
}
