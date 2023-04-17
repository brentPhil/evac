<?php

// $conn = new mysqli("localhost", "root", "root", "evacuation");

$conn = new mysqli("localhost", "root", "", "evacuation");

if (!defined('base_url')) define('base_url', 'http://localhost/evac/');
if (!defined('base_app')) define('base_app', str_replace('\\', '/', __DIR__) . '/');
