<?php

class amfTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'AMF-file'),
    ));

    // add your own options here
    $this->addOptions(array(
      new sfCommandOption('split', null, sfCommandOption::PARAMETER_NONE, 'Split result to multiply files'),
    ));

    $this->namespace        = '';
    $this->name             = 'amf';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [amf|INFO] task does things.
Call it with:

  [php symfony amf|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // add your code here
    $this->configuration->registerZend();

    $this->logSection('file', 'Loading...');
    $content = file_get_contents($arguments['file']);
    $istream = new Zend_Amf_Parse_InputStream($content);
    $parser = new Zend_Amf_Parse_Amf3_Deserializer($istream);
    $this->logSection('file', 'Parsing...');    
    $r = $parser->readTypeMarker();
    $this->logSection('file', 'done!');
    $this->logBlock(sprintf('Result type: %s', gettype($r)), 'INFO');
    if(is_array($r) || is_object($r)) {
    	foreach ($r as $k => $v){
    		$this->logSection('element', $k);
    		if($options['split']) {
    			file_put_contents($arguments['file'].'.'.$k.'.json', json_encode($v));
    		}
    	}
    	if(!$options['split']) {
    		file_put_contents($arguments['file'].'.json', json_encode($r));
    	}
    }
    else {
    	file_put_contents($arguments['file'].'.json', json_encode($r));
    }    
  }
}
