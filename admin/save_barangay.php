<?php
  require_once 'connection.php';

  $id = $_POST['id'];
  $name = $_POST['name'];
  $captain = $_POST['captain'];
  $secretary = $_POST['secretary'];
  $contact_number = $_POST['contact_number'];
  $lat = $_POST['lat'] ?? '';
  $long = $_POST['long'] ?? '';
  
  $conn->query("UPDATE barangay SET name = '$name', captain='$captain', secretary= '$secretary', contact_no='$contact_number' , lat = '$lat', lng = '$long' WHERE id = " .$id);

  header('Content-Type: application/json; charset=utf-8');
  
  echo json_encode([
    'success' => true,
  ]);

?>