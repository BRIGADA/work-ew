<?php
use_javascript('http://code.highcharts.com/highcharts.js');
use_helper('JavascriptBase');

function Highcharts($title, $series, $options = array(), $container = null)
{
	$prefix = '';
	if(is_null($container))
	{
		$container = sprintf('highcharts-%s', sha1(rand(0, 1000000)));
		$prefix = content_tag('div', '', array('id'=>$container) );
	}
	
	$options['title']['text'] = $title;		
	
	if(!isset($options['series']))
	{
		$options['series'] = array();
	}

	foreach($series as $name => $data)
	{
		if(is_a($data, 'sfOutputEscaperArrayDecorator'))
		{
			$data = $data->getRawValue();
		}
		$options['series'][] = array('name'=>$name, 'data'=>$data);
	}
	
	return $prefix.javascript_tag(sprintf('$(function(){$("#%s").highcharts(%s);});', $container, json_encode($options)));
}

