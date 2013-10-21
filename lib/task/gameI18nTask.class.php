<?php
class gameI18nTask extends sfBaseTask {
	protected function configure() {
		// add your own arguments here
		$this->addArguments ( array (new sfCommandArgument ( 'lang', sfCommandArgument::REQUIRED, 'locale' ), new sfCommandArgument ( 'file', sfCommandArgument::REQUIRED, 'XML-file' )) );
		
		// // add your own options here
		// $this->addOptions(array(
		// new sfCommandOption('my_option', null, sfCommandOption::PARAMETER_REQUIRED, 'My option'),
		// ));
		
		$this->namespace = 'game';
		$this->name = 'i18n';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
The [game:i18n|INFO] task does things.
Call it with:

  [php symfony game:i18n|INFO]
EOF;
	}
	protected function execute($arguments = array(), $options = array()) {
		// add your code here
		libxml_use_internal_errors ( true );
		$file = simplexml_load_file ( $arguments ['file'], "SimpleXMLElement" );
		
		if (! $file) {
			foreach ( libxml_get_errors () as $error ) {
				$this->logSection(sprintf('%u:%u', $error->line, $error->column), trim($error->message));
			}
			exit();
		}
		
		foreach ( $file->children () as $n ) {
			$tag = $n->getName ();
			if (preg_match ( '/^units\.(.+)\.name$/', $tag, $matches )) {
//				$this->logSection ( $matches [1], $n );
			}
			if(preg_match('/^units\.(.+)\.(.+)$/', $tag, $matches))
			{
				$this->logSection($matches[2], sprintf('%s: %s', $matches[1], $n));
			}
		}
	}
}
