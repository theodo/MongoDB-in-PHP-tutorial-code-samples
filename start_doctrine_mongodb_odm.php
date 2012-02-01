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


// Get data
$captors = json_decode(file_get_contents('https://lights.theodo.fr/'), true);
var_dump($captors);


// Save data
$measurement = new \Documents\Measurement($captors);
$dm->persist($measurement);
$dm->flush();

var_dump($measurement);


// Find one method 1
  $measurement = $dm
    ->createQueryBuilder('Documents\Measurement')
    ->field('datetime')->equals($measurement->datetime)
    ->getQuery()->getSingleResult();

  var_dump($measurement);


// Find one method 2

  $measurement = $dm
    ->getRepository('Documents\Measurement')
    ->findOneBy(array('datetime' => $measurement->datetime));

  var_dump($measurement);


// Updating an object
  $measurement->captor1 += 1;
  $dm->flush();

  var_dump($measurement);



// Removing an object

  $dm->remove($measurement);
  $dm->flush();

  $measurement = $dm
    ->getRepository('Documents\Measurement')
    ->find($measurement->id);

  var_dump($measurement);

  $building = new \Documents\Building('Headquarters', 'Paris');
  $dm->persist($building);
  $dm->flush();


  $measurements = $dm
    ->getRepository('Documents\Measurement')
    ->findAll();

  foreach($measurements as $measurement)
  {
      $building->addMeasurement($measurement);
  }
  $dm->flush();


// Map - reduce

  $qb = $dm->createQueryBuilder('Documents\Measurement')
    ->sort('datetime', 'asc')
    ->map("
      function() {
        var data1 = {'datetime': this.datetime, 'captor': this.captor1};
        var data2 = {'datetime': this.datetime, 'captor': this.captor2};

        emit('captor1', {'start': data1, 'end': data1, 'time_on': 0});
        emit('captor2', {'start': data2, 'end': data2, 'time_on': 0});
      }
    ")
    ->reduce("
    function(k, vals) {
      var time_on = 0;
      var time_spent = function(val1, val2)
      {
          var threshold = 80;
          var timeb_on = val1.time_on + val2.time_on;

          if (val1.end.captor < threshold && val2.start.captor < threshold)
          {
              // we suppose light was on between the two events
              timeb_on += val2.start.datetime - val1.end.datetime;
          }
          else if (val1.end.captor <= threshold || val2.start.captor <= threshold)
          {
              // we suppose light was on during half the time
              timeb_on += (val2.start.datetime - val1.end.datetime) / 2;
          }
          return timeb_on;
      };
      for (var i in vals)
      {
        if (i == 0) { continue; }
        time_on += time_spent(vals[i-1], vals[i]);
      }
      return {'start': vals[0].start, 'end': vals[vals.length-1].end, 'time_on': time_on};
    }"
  );

  $query = $qb->getQuery();
  $results = $query->execute();
  $results_a = iterator_to_array($results);

  echo 'Light was on in toilet 1 during ' . $results_a[0]['value']['time_on'] / 1000 . " seconds.\n";
  echo 'Light was on in toilet 2 during ' . $results_a[1]['value']['time_on'] / 1000 . " seconds.\n";


