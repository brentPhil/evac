<?php

  require_once 'connection.php';

  $id = $_POST['id'];

  $conn->query("UPDATE evacuees SET status = 0 WHERE id = " .$id);

  header('Content-Type: application/json; charset=utf-8');
  
  echo json_encode([
    'success' => true,
  ]);

?>