<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

ini_set('display_errors', 0);

$loader = require_once __DIR__.'/vendor/autoload.php';

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass($class);
    return class_exists($class, false);
});
AnnotationRegistry::registerFile(__DIR__.'/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

$app = require __DIR__.'/src/app.php';
require __DIR__.'/src/controllers.php';
$app->run();