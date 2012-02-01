<?php


  // Connect
  $mongo = new Mongo('mongodb://localhost');


  // Select a database
  $db = $mongo->selectDB('dbname');


  // Selecting a collection
  $coll = $db->selectCollection('measurements');


  // Get data
  $captors = json_decode(
    file_get_contents('https://lights.theodo.fr/')
    , true
  );
  var_dump($captors);


  // Create a document
  $datetime = new MongoDate(time());
  $measurement = $captors + array('datetime' => $datetime);
  $coll->insert($measurement);

  var_dump($measurement);
  echo $measurement['_id'] . "\n";

  // Update a document
  $measurement = $coll->findOne(array('datetime' => $datetime));
  $measurement['captor1'] += 1;
  $measurement['captor2'] += 1;
  $coll->save($measurement);

  var_dump($measurement);
  echo $measurement['_id'] . "\n";


  // Atomic update of a document
  $coll->update(
    array(
      'datetime' => $datetime
    ),
    array(
      '$inc' => array(
		'captor1' => 1,
		'captor2' => 1
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
