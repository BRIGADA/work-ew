<?php

class gameTranslationTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('lang', sfCommandArgument::REQUIRED, 'Language'),
      new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'XML-file'),
    ));

    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'game';
    $this->name             = 'translation';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:translation|INFO] task does things.
Call it with:

  [php symfony game:translation|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    
//    $dir = sfConfig::get('sf_app_i18n_dir');
//    $this->logBlock($dir, 'INFO');
//    return;
    
    $xml = simplexml_load_file($arguments['file'], "SimpleXMLElement", LIBXML_NOERROR |  LIBXML_ERR_NONE);
    
    $results = array();
    
    foreach ($xml->children() as $n)
    {
    	$path = explode('.', $n->getName());
   		$collection = count($path) > 1 ? ('ew-'.array_shift($path)) : 'ew';
   		$results[$collection][implode('.', $path)] = strval($n);
    }
    
    $imp = new DOMImplementation();

    foreach ($results as $collection => $items)
    {
    	$this->logBlock($collection, 'INFO');
    	
    	$dtd = $imp->createDocumentType('xliff', '-//XLIFF//DTD XLIFF//EN', 'http://www.oasis-open.org/committees/xliff/documents/xliff.dtd');
    	$out = $imp->createDocument(null, 'xliff', $dtd);
    	$out->formatOutput = true;
    	$out->preserveWhiteSpace = false;
    	$out->xmlVersion = '1.0';
    	$out->version = '1.0';
    	$out->documentElement->setAttribute('version', '1.0');
    	$file_element = $out->createElement('file');
    	$out->documentElement->appendChild($file_element);
    	$file_element->setAttribute('source-language', 'en');
    	$file_element->setAttribute('target-language', $arguments['lang']);
    	$file_element->setAttribute('datatype', 'plaintext');
    	$file_element->setAttribute('original', $collection);
    	$file_element->setAttribute('product-name', $collection);
    	$file_element->appendChild($out->createElement('header'));
    	$body_element = $out->createElement('body');
    	$file_element->appendChild($body_element);
    	
    	$curr_id = 1;
    	
    	foreach($items as $k => $v)
    	{
    		$tu_element = $out->createElement('trans-unit');
    		$body_element->appendChild($tu_element);
    		$tu_element->setAttribute('id', $curr_id++);
    		$tu_element->appendChild($out->createElement('source', $k));
    		$tu_element->appendChild($out->createElement('target', $v));
    	}
    	
    	$out->save("apps/frontend/i18n/{$arguments['lang']}/{$collection}.xml");
    }
  }
}
