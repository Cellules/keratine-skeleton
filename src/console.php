#!/usr/bin/env /Applications/MAMP/bin/php/php5.5.3/bin/php
<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

$loader = require_once __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass($class);
    return class_exists($class, false);
});
AnnotationRegistry::registerFile(__DIR__.'/../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

$app = require __DIR__.'/app.php';

$console = new Application('Console', 'n/a');

$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db'         => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($app['orm.em']->getConnection()),
    'em'         => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($app['orm.em']),
    'users'      => new \Keratine\Console\Helper\UserProviderHelper($app['users']),
    'zendsearch' => new \Keratine\Console\Helper\ZendSearchHelper($app['zendsearch'], $app['zendsearch.indices']),
));
$console->setHelperSet($helperSet);

// Doctrine CLI
Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($console);

// Keratine CLI
$console->add(new Keratine\Console\Command\UserCreateCommand());
$console->add(new Keratine\Console\Command\LuceneCountCommand());
$console->add(new Keratine\Console\Command\LuceneDeleteCommand());
$console->add(new Keratine\Console\Command\LuceneIndexCommand());

// return $console;
$console->run();