<?php 

  include_once '../../app/databaseObject.php';

  class Person extends databaseObject{
    
    private $table = 'persons';
    // Person Properties
    public $id;
    public $person_id;
    public $type;
    public $name;
    public $birthdate;
    public $lat;
    public $lng;
    

    // Get Tree Of Specific Person
    public function read() {

      // Create query
      $query = 'SELECT  p.id, p.person_id, p.type, p.name, p.birthdate, p.lat, p.lng FROM ' . $this->table . ' p';
      
      // GetPerson/{$person_id}
      if(isset($this->person_id)){ $query .= ' WHERE p.person_id = ? '; $r = $this->executeQuery($query, [1 => $this->person_id]); }

      // GetPerson/{$start_date}&{$end_date}
      if(isset($this->start_date) && isset($this->end_date)){ $query .= ' WHERE p.birthdate BETWEEN ? AND ? '; $r = $this->executeQuery($query ,[1 => $this->start_date, 2 => $this->end_date]); }
      
      return $r;
    }


    // Get Single Person
    public function read_single() {
      // Create query
      $query = 'SELECT  p.id, p.person_id, p.type, p.name, p.birthdate, p.lat, p.lng FROM ' . $this->table . ' p WHERE p.id = ? LIMIT 0,1';
      
      // Prepare statement
      $stmt = $this->conn->prepare($query);
      
      // Bind ID
      $stmt->bindParam(1, $this->id);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // Set properties
      $this->id = $row['id'];
      $this->person_id = $row['person_id'];
      $this->type = $row['type'];
      $this->name = $row['name'];
      $this->birthdate = $row['birthdate'];
      $this->lat = $row['lat'];
      $this->lng = $row['lng'];

    }


    // Create Post
    public function create() {
      // Create query
      $query = 'INSERT INTO ' . $this->table . ' SET name = :name, birthdate = :birthdate, lat = :lat, lng = :lng, type = :type, person_id = :person_id';

      $r = $this->executeQuery($query, [':name' => $this->name, ':birthdate' => $this->birthdate, ':lat' => $this->lat, ':lng' => $this->lng, ':type' => $this->type, ':person_id' => $this->person_id]);

      // Execute query
      if($r) { return $this->conn->lastInsertId(); }else{ return $r; }

    }


  // Update Post
  public function update() {
    // Create query
    $query = 'UPDATE ' . $this->table . ' SET person_id = :person_id WHERE id = :id';

    $r = $this->executeQuery($query, [':person_id' => $this->person_id, ':id' => $this->id]);

    // Execute query
    if($r) { return true; }else{ return $r; }
  }


  public function getLocationDetails($lat, $lng)
  {
    if(isset($lat) && isset($lng)){
      $GeoInfo  = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true&key=AIzaSyDqET1nIDZzMGEieGANkEF_xB1RSCkJTjk";
      
      //  Initiate curl
      $ch = curl_init();

      // Set the url
      curl_setopt($ch, CURLOPT_URL,$GeoInfo);

      // Execute
      json_decode(curl_exec($ch) , true);
    }
  }



}