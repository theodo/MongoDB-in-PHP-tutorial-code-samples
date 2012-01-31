<?php

require "vendor/.composer/autoload.php";

$config = new \Doctrine\MongoDB\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__."/Documents"));
$config->setMetadataDriverImpl($driverImpl);
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$dm = \Doctrine\ORM\DocumentManager::create($mongo, $config);
