<?php
  require_once 'connection.php';

  $id = $_POST['id'];
  $name = $_POST['name'];
  $date = $_POST['date'];
  $type = $_POST['type'];  
  $platenumber = $_POST['platenumber'];
  
  $conn->query("UPDATE mpv SET name = '$name' , `date` = '$date', `platenumber` = '$platenumber', `status` = $type where id = $id ");

  header('Content-Type: application/json; charset=utf-8');
  
  echo json_encode([
    'success' => true,
  ]);

?>