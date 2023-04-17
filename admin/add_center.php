<?php
session_start(); // start the session

require_once 'connection.php';

if (isset($_POST['saveCenter'])) {
    try {
        // Get input values
        $centername = $_POST['centername'];
        $address = $_POST['address'] ?? '';
        $manager = $_POST['manager'];
        $guard = $_POST['guard'];
        $contactinfo = $_POST['contactinfo'];
        $brgy = $_POST['brgy'];
        $lat = $_POST['lat'];
        $long = $_POST['long'];
        $capacity = $_POST['capacity'];
        $type = $_POST['type'];
        $date = date('Y-m-d H:i:s');

        // Insert new evacuation center into database
        $result = $conn->query("
            INSERT INTO `evacuation_center` (id, center, camp_manager, guard,  address, contact, lat, lng, status, date_added, capacity, type) 
            VALUES(null,'$centername', '$manager', '$guard', '$address', '$contactinfo', '$lat', '$long', 1, '$date', '$capacity', '$type')");

        if (!$result) {
            throw new Exception('Unable to insert data into evacuation_center table');
        }

        // Get the ID of the new evacuation center
        $id = mysqli_insert_id($conn);

        // Insert related barangays into evacuation_barangay table
        foreach ($brgy as $each) {
            $result = $conn->query("INSERT INTO `evacuation_barangay` (evac_id, barangay_id) VALUES ($id, $each)");

            if (!$result) {
                throw new Exception('Unable to insert data into evacuation_barangay table');
            }
        }

        // Handle image upload, if any
        if (isset($_FILES["image_path"])) {
            $path = "uploads/";
            $base = '../' . $path;
            if (!file_exists($base)) {
                mkdir($base);
            }
            $target_dir = $base . $id . "/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir);
            }
            $target_file = $target_dir . basename($_FILES["image_path"]["name"]);
            $file_path = $path . $id . "/" . basename($_FILES["image_path"]["name"]);

            if (move_uploaded_file($_FILES["image_path"]["tmp_name"], $target_dir . $_FILES['image_path']['name'])) {
                // Update image_path field in database
                $result = $conn->query(
                    "
                 UPDATE `evacuation_center` SET image_path = '$file_path'
                 WHERE id = $id "
                );

                if (!$result) {
                    throw new Exception('Unable to update image_path field in evacuation_center table');
                }
            } else {
                throw new Exception('Unable to move uploaded file to target directory');
            }
        }

        $_SESSION['isSave'] = true;
        header('Location: evacuation-center.php');
        exit();
    } catch (Exception $e) {
        // Handle the exception
        echo 'Error: ' . $e->getMessage();
    }
}