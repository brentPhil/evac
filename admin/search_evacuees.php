<?php
  require_once 'connection.php';

  $name = $_GET['term']['term'];
  
  $brgyQuery = $conn->query("
  SELECT bi.*, b.name as brgy FROM barangay_info bi
  LEFT JOIN barangay b on bi.barangay_id = b.id
  WHERE bi.status = 1 and bi.lastname LIKE '%$name%' ");
  
  $persons = $brgyQuery->fetch_all();

  $list = [];
  foreach ($persons as $brg) {
    $list[] = [
        'id' => $brg[0],
        'name' => $brg[1] . ', ' .$brg[2] . ' from ' . $brg[10]
    ];
  }

  header('Content-Type: application/json; charset=utf-8');
  
  echo json_encode([
    'items' => $list,
  ]);

?>