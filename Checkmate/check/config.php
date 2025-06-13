<?php
$host = 'localhost';
$db = 'checkmate';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
require_once __DIR__ . '/vendor/autoload.php';
$client = new Google\Client();
$client->setClientId("104312703977-chneorihjpj383gmpbrevfi9aq80vnpm.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/checkmate/google-callback.php");
$client->addScope("email");
$client->addScope("profile");




?>