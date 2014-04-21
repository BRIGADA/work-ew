<?php

class gameMeltdownTask extends sfBaseTask
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
    $this->name             = 'meltdown';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [game:meltdown|INFO] task does things.
Call it with:

  [php symfony game:meltdown|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    
    $current = MeltdownTable::getCurrent();
    $this->logSection('current', $current ? $current : 'NONE');
    
    while ($content = trim(fgets(STDIN)))
    {
        if(preg_match('/meltdown=([a-z0-9]{40})/', $content, $matches)) {
            if($matches != $current) {
                $current = MeltdownTable::setCurrent($matches[1]);
                $this->logSection('new', $current);
            }
        }
    }
  }
}
