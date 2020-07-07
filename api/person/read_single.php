<?php 
  
  // Inititalizing The Page
  include_once '../../app/init.php';

  // Get Person Id ("/GetPerson/{$person_id}")
  $person->id = isset($_GET['id']) ? $_GET['id'] : null;

  // Get Persons Between Dates ("/GetPersonBetweenDates/{$date1}/{$date2}")
  $person->start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
  $person->end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

  // Get person Depend On Id ("if the person_id is null the result will be null")
  $person->read_single();

  //Getting The father and grandfather names
  $the_name = explode(" ", $person->name);

  // Create array Of Result
  $person_arr = array('id' => $person->id, 'type' => $person->type, 'name' => $person->name, 'father_name' => $the_name[1], 'grandfather_name' => $the_name[2] , 'birthdate' => $person->birthdate, 'lat' => $person->lat, 'lng' => $person->lng);

  // Get Location Details Depending On Google Map API
  $person->getLocationDetails($person->lat, $person->lng);

  // Get person_id Field For Getting The Tree
  $person->person_id = isset($person->person_id) ? $person->person_id : $person->id;

  // Get person's Tree if Passing person id 
  // Get Persons Between Start And End Date If Passing Start_date And End_date
  $result = $person->read();

  // Get row count For The Tree
  $num = $result->rowCount();
  
  // Check if any persons
  if($num > 0) {
    // person array
    $persons_arr = array();
    
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      // Getting The Row
      extract($row);

      //Getting The father and grandfather names
      $the_name = explode(" ", $name);

      // Assign To array
      $person_item = array('id' => $id, 'type' => $type, 'name' => $name, 'father_name' => $the_name[1], 'grandfather_name' => $the_name[2] , 'birthdate' => $birthdate, 'lat' => $lat, 'lng' => $lng);

      // Adding To Persons_arr
      array_push($persons_arr, $person_item);
    }

    // Turn to JSON & output
    if($person_arr["id"] != null){
      // Get Person Id ("/GetPerson/{$person_id}")
      echo(json_encode(['person' => $person_arr, 'tree' => $persons_arr]));
    } else {
      // Get Persons Between Dates ("/GetPersonBetweenDates/{$date1}/{$date2}")
      echo(json_encode(['persons' => $persons_arr]));
    }

  } else {
    // No Persons
    echo json_encode(['message' => 'No Persons Found']);
  }

 