<?php
  require_once 'connection.php';

  $id = $_POST['id'];
  
  $calamity = $_POST['calamity'];
  $evacuation_center_id = $_POST['evacuation_center_id'];
  $person_id = $_POST['person_id'];
  $head_person_id = $_POST['head_person_id'];
  
  $conn->query("
      UPDATE `evacuees` SET evacuation_center_id = $evacuation_center_id,
      calamity_id = $calamity, person_id = $person_id, head_person_id = $head_person_id
      WHERE id = $id "
  	);
  	


  header('Content-Type: application/json; charset=utf-8');
  
  echo json_encode([
    'success' => true,
  ]);

?>