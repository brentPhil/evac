<?php
  require_once 'connection.php';

  $id = $_POST['id'];
  $time = $_POST['time'];
  $date = $_POST['date'];
  $mpv_id = $_POST['mpv_id'];  
  $evo_name = $_POST['evo_name'];
  $route = $_POST['route'];
  $appointment = $_POST['appointment'];
  $type = $_POST['type'];

  $sql = "UPDATE mpv_trans SET `date` = '$date',
  `time` = '$time', `mpv_id` = $mpv_id, evo_name = '$evo_name', 
  route = '$route', appointment = '$appointment', type = '$type'
    where id = $id";
  
  $conn->query($sql);

  header('Content-Type: application/json; charset=utf-8');
  
  echo json_encode([
    'success' => true,
  ]);

?>