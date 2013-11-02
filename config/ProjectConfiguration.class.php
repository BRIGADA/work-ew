<?php

require_once dirname(__FILE__).'/../../vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  static protected $zendLoaded = false;
 
  static public function registerZend()
  {
    if (self::$zendLoaded)
    {
      return;
    }
 
    set_include_path(dirname(__FILE__).'/../../vendor'.PATH_SEPARATOR.get_include_path());
    require_once dirname(__FILE__).'/../../vendor/Zend/Loader/Autoloader.php';
    Zend_Loader_Autoloader::getInstance();
    self::$zendLoaded = true;
  }

  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
  }
}
