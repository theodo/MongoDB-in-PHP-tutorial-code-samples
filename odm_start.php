<?php

require "vendor/.composer/autoload.php";

use Doctrine\Common\ClassLoader,
    Doctrine\ODM\MongoDB\Configuration,
    Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver,
    Doctrine\ODM\MongoDB\DocumentManager,
    Doctrine\MongoDB\Connection;


$config = new Configuration();
$config->setProxyDir(__DIR__ . '/cache');
$config->setProxyNamespace('Proxies');

$config->setHydratorDir(__DIR__ . '/cache');
$config->setHydratorNamespace('Hydrators');

$annotationDriver = $config->newDefaultAnnotationDriver(array(__DIR__ . '/Documents'));
$config->setMetadataDriverImpl($annotationDriver);
AnnotationDriver::registerAnnotationClasses();

$dm = DocumentManager::create(new Connection(), $config);



// Document classes
$classLoader = new ClassLoader('Documents', __DIR__);
$classLoader->register();


$measurement = new \Documents\Measurement(981, 954);
$dm->persist($measurement);
$dm->flush();

var_dump($measurement);

$measurement = $dm->createQueryBuilder('Documents\Measurement')
  ->field('captor1')->equals(981)->getQuery();
var_dump(iterator_to_array($measurement));
