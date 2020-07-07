<?php 

  include_once '../../app/init.php';
  include_once '../../app/validation.php';

  // Get raw personed data
  $data = json_decode(file_get_contents("php://input"));


  //validations For Presence
  $required_fields = array('name' => $data->name, 'birthdate' => $data->birthdate , 'latitude' => $data->lat, 'longitude' => $data->lng, 'type' => $data->type);
  if($data->type !== 'son') { $required_fields['person_id'] = $data->person_id; }
	Validation::validate_presence($required_fields);
  
  //validations For Max Length
	$fields_with_max_length =array($data->name => 500);
	Validation::validate_max_length($fields_with_max_length);
	
  
  // if Errors It Will Be Shown
	if(!empty(Validation::$errors)){		
    http_response_code(422);
    echo json_encode(Validation::$errors);
	  die();
	}
	

  // Initialize Inputs
  $person->name = $data->name;

  // Reform Date Input
  $time = strtotime($data->birthdate);
  $newFormat = date("Y-m-d", $time);
  $person->birthdate = $newFormat;

  // Initialize Inputs
  $person->lat = $data->lat;
  $person->lng = $data->lng;
  $person->type = $data->type;
  $person->person_id = $data->person_id;
  

  // Create person
  if($result = $person->create()) {

    // if person type is the oldest son the person_id will be equal to id
    $person->id = $result;
    if($person->type == 'son') { $person->person_id = $result;  $person->update(); }
    
    // retun person created
    echo json_encode($person);

  } else {
    echo json_encode(array('message' => 'Something is wrong Please Try Again'));
  }
