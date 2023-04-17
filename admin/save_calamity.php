<?php
  require_once 'connection.php';

  $id = $_POST['id'];
  $name = $_POST['calamity'];
  $date = $_POST['date'];
  $type = $_POST['type'];
  
  $conn->query("UPDATE calamity SET name = '$name' , date_added = '$date', `type` = $type WHERE id = " .$id);

  header('Content-Type: application/json; charset=utf-8');
  
  echo json_encode([
    'success' => true,
  ]);

?>