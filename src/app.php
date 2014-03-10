<?php
use Keratine\Application\Application;

use Keratine\Config\ConfigurationLoader;

$loader = new ConfigurationLoader(__DIR__.'/../config/', array('config.yml', 'parameters.yml'));
$loader->addParameter('root_dir', realpath(__DIR__.'/..'));
$config = $loader->load();

$app = new Application($config);

return $app;