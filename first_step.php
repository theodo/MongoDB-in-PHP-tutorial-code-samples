<?php


  // Connect
  $mongo = new Mongo('mongodb://localhost');


  // Select a database
  $db = $mongo->selectDB('dbname');


  // Selecting a collection
  $coll = $db->selectCollection('measurements');


  // Create a document
  $datetime = new MongoDate($datetime);
  $measurement = array(
	'captor1' => 981,
	'captor2' => 954,
	'datetime' => $datetime
  );
  $coll->insert($measurement);

  var_dump($measurement);
  echo $measurement['_id'] . "\n";

  // Update a document
  $measurement = $coll->findOne(array('datetime' => $datetime));
  $measurement['captor1'] = 982;
  $measurement['captor2'] = 955;
  $coll->save($measurement);

  var_dump($measurement);
  echo $measurement['_id'] . "\n";


  // Atomic update of a document
  $coll->update(
    array(
      'datetime' => $datetime
    ),
    array(
      '$set' => array(
		'captor1' => 983,
		'captor2' => 956
	  )
	)
  );

  $measurement = $coll->findOne(array('datetime' => $datetime));
  var_dump($measurement);
  echo $measurement['_id'] . "\n";


  // Remove a document
  $coll->remove(array('datetime' => $datetime));
  $measurement = $coll->findOne(array('datetime' => $datetime));
  var_dump($measurement);
