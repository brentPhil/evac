<?php
	
	 require_once 'connection.php';

	session_start();
	if(!ISSET($_SESSION['admin_id']))
	{
		header('location:index.php');
	}

	$adminId = $_SESSION['admin_id'];

	$query = $conn->query("
		SELECT u.*, r.role 
		FROM users u 
		LEFT JOIN role r on u.role_id = r.id where u.id = $adminId");

	$admin = $query->fetch_assoc();

	$adminEvac = $admin['evacuation_center_id'];

	$q4 = $conn->query("SELECT * FROM evacuation_center WHERE id = $adminEvac");
    $adminEvacuationCenter = $q4->fetch_assoc();
    $evacuation_center_id = $adminEvacuationCenter['id'];

    $_SESSION['adminEvacuationCenter'] = $adminEvacuationCenter;

	$isAdmin = $admin['role_id'] == 1 ? true : false;
?>