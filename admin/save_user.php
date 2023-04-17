<?php
  require_once 'connection.php';

  $id = $_POST['id'];
  $fullname = $_POST['fullname'];
  $role = $_POST['role'];
  $contactinfo = $_POST['contactinfo'];
  $designation = $_POST['designation'];
  $email = $_POST['email'];
  $evacuation_center = $_POST['evacuation_center'];

	$conn->query("
      UPDATE `users` SET fullname = '$fullname', evacuation_center_id = $evacuation_center,
      role_id = '$role', contactinfo = '$contactinfo', designation = '$designation', 
      email = '$email' WHERE id = $id "
  	);
  	

  // $conn->query("DELETE FROM evacuation_barangay WHERE evac_id = $id");

  // foreach ($brgy as $each) {
  //     $conn->query("INSERT INTO `evacuation_barangay` (evac_id, barangay_id) VALUES ($id, $each)");
  // }

  header('Content-Type: application/json; charset=utf-8');
  
  echo json_encode([
    'success' => true,
  ]);

?>