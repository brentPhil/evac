

<?php
require_once 'connection.php';

$id = $_POST['id'];
$center = $_POST['center'];
$brgy = $_POST['brgy'];
$manager = $_POST['manager'];
$guard = $_POST['guard'];
$address = $_POST['address'];
$econtact = $_POST['econtact'];
$elat = $_POST['elat'];
$elong = $_POST['elong'];
$capacity = $_POST['capacity'];
$type = $_POST['type'];



$path = "uploads/";
$base = '../' . $path;
if (!file_exists($base))
  mkdir($base);
$target_dir = $base . $id . "/";
if (!file_exists($target_dir))
  mkdir($target_dir);
$target_file = $target_dir . basename($_FILES["image_path"]["name"]);
$file_path = $path . $id  . "/" . basename($_FILES["image_path"]["name"]);
$image_path = $file_path;




if (move_uploaded_file($_FILES["image_path"]["tmp_name"], $target_dir . $_FILES['image_path']['name'])) {
  $conn->query(
    "
        UPDATE `evacuation_center` SET center = '$center', 
        camp_manager = '$manager', 
        guard = '$guard', 
        address = '$address', contact = '$econtact', lat = '$elat', 
        lng = '$elong',
        capacity = '$capacity' ,
        type = '$type' ,
        image_path = '$image_path'
        WHERE id = $id "
  );


  $conn->query("DELETE FROM evacuation_barangay WHERE evac_id = $id");

  foreach ($brgy as $each) {
    $conn->query("INSERT INTO `evacuation_barangay` (evac_id, barangay_id) VALUES ($id, $each)");
  }

  header('Content-Type: application/json; charset=utf-8');

  echo json_encode([
    'success' => true,
  ]);
} else {
  echo json_encode([
    'success' => false,
  ]);
}




?>