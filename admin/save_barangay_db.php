<?php
  require_once 'connection.php';

  $id = $_POST['id'];
  $lastname = $_POST['lastname'];
  $firstname = $_POST['firstname'];
  $age = $_POST['age'];
  $gender = $_POST['gender'];
  $address = $_POST['address'];
  $contact_number = $_POST['contact_number'];
  
  $conn->query("UPDATE barangay_info SET lastname = '$lastname', firstname='$firstname', age= '$age', contact_number='$contact_number' , gender='$gender', address = '$address' WHERE id = " .$id);

  header('Content-Type: application/json; charset=utf-8');
  
  echo json_encode([
    'success' => true,
  ]);

?>