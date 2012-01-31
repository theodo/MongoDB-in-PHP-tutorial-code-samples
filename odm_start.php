<?php

require "vendor/.composer/autoload.php";

use Doctrine\Common\ClassLoader,
    Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\ODM\MongoDB\DocumentManager,
    Doctrine\MongoDB\Connection,
    Doctrine\ODM\MongoDB\Configuration,
    Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

// Document classes
$classLoader = new ClassLoader('Documents', __DIR__);
$classLoader->register();;


$cache = new \Doctrine\Common\Cache\ArrayCache;

$config = new Configuration();
$config->setMetadataCacheImpl($cache);

$config->setProxyDir(__DIR__ . '/cache');
$config->setProxyNamespace('Proxies');

$config->setHydratorDir(__DIR__ . '/cache');
$config->setHydratorNamespace('Hydrators');

$reader = new AnnotationReader();
$reader->setDefaultAnnotationNamespace('Doctrine\ODM\MongoDB\Mapping\\');
$config->setMetadataDriverImpl(new AnnotationDriver($reader, __DIR__ . '/Documents'));


AnnotationDriver::registerAnnotationClasses();

$dm = DocumentManager::create(new Connection(), $config);








$newProject = new \Documents\Project('Another Project');

$dm->persist($newProject);
$dm->flush();

var_dump($newProject);
