<?php
session_start(); // start the session

require_once 'connection.php';

if (isset($_POST['save'])) {

    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $age = $_POST['age'];
    $brgy = $_POST['brgy'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $date = date('Y-m-d H:i:s');

    $conn->query("INSERT INTO `barangay_info` (id, barangay_id, lastname, firstname, age, contact_number, gender, address, date_added, status) VALUES(
    null, '$brgy', '$lastname', '$firstname', '$age', '$contact_number', '$gender', '$address', '$date', 1)");

    $_SESSION['isSave'] = true; // set the session variable
    // Redirect the user to a success page after the data has been saved
    header('Location: barangay_db.php');
    exit();
}

if (isset($_POST['Up_mpv'])) {

    $name = $_POST['name'];
    $platenumber = $_POST['platenumber'];
    $date = $_POST['date'];
    $type = $_POST['type'];

    $conn->query("INSERT INTO `mpv` (id, name, date, platenumber, `status`, is_deleted) VALUES(
    NULL, '$name', '$date', '$platenumber', '$type', 0)");

    $_SESSION['isSave'] = true; // set the session variable
    header('Location: mpv.php');
    exit();
}

if (isset($_POST['brgy'])) {
    // Validate and sanitize input data
    $barangay = htmlspecialchars($_POST['barangay']);
    $captain = htmlspecialchars($_POST['captain']);
    $secretary = htmlspecialchars($_POST['secretary']);
    $contact_number = htmlspecialchars($_POST['contact_number']);
    $lat = htmlspecialchars($_POST['lat'] ?? '');
    $long = htmlspecialchars($_POST['long'] ?? '');

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO `barangay` (name, captain, secretary, contact_no, lat, lng, status) 
                            VALUES (?, ?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("ssssss", $barangay, $captain, $secretary, $contact_number, $lat, $long);

    // Execute the query and handle errors
    if ($stmt->execute()) {
        $_SESSION['isSave'] = true; // set the session variable
        header('Location: barangay.php');
        exit();
    } else {
        // Handle the error
        $error_message = "Failed to insert data: " . $stmt->error;
        // Log the error or return the error message to the user
    }
    $stmt->close();
}

if (isset($_POST['evacuee'])) {

    // Get form data
    $date_added  = $_POST['date_added'];
    $evacuation_center_id = $_POST['evacuation_center_id'];
    $calamity_id = $_POST['calamity'];
    $person_id = $_POST['person_id'];
    $head_person_id = $_POST['head_person_id'];
    $is_present = $_POST['is_present'];
    $remarks = $_POST['remarks'];

    // Set date_added variable to current date and time
    $current_date_time = date('Y-m-d H:i:s');

    // Insert data into database for each person
    foreach ($person_id as $person) {
        $stmt = $conn->prepare("INSERT INTO `evacuees` (id, status, date_added, evacuation_center_id, calamity_id, person_id, head_person_id, is_present, remarks) VALUES(null, 1, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiiiis", $current_date_time, $evacuation_center_id, $calamity_id, $person, $head_person_id, $is_present, $remarks);
        $stmt->execute();
    }

    // Set session variable and redirect
    $_SESSION['isSave'] = true;
    header('Location: add-evacuees2.php');
    exit();
}


