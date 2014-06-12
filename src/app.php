<?php
use Keratine\Application\Application;

use Symfony\Component\Config\FileLocator;
use Keratine\Config\ConfigurationLoader;
use Keratine\Config\Loader\YamlFileLoader;

$configLocator = new FileLocator(__DIR__.'/../config/');

// load configuration
$loader = new ConfigurationLoader($configLocator, array('config.yml'));
$loader->addParameter('root_dir', realpath(__DIR__.'/..'));
$config = $loader->load();

// load parameters
$loader = new YamlFileLoader($configLocator);
$parameters = $loader->load('parameters.yml');

$app = new Application($config, $parameters);

return $app;