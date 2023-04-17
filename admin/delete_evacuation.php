<?php

require_once 'connection.php';

$id = $_POST['id'];

$result = $conn->query("DELETE FROM evacuation_center WHERE id = ".$id);

header('Content-Type: application/json; charset=utf-8');

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

