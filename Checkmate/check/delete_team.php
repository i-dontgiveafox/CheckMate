<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_start(); // Capture unexpected output

$conn = new mysqli("localhost", "root", "", "checkmate");
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

$team_id = $_GET['id'] ?? null;
if (!$team_id || !is_numeric($team_id)) {
    die(json_encode(['error' => 'Invalid team ID']));
}

// Delete team and its members
if (!$conn->query("DELETE FROM team WHERE team_id = $team_id") || !$conn->query("DELETE FROM team_members WHERE team_id = $team_id")) {
    die(json_encode(['error' => 'Failed to delete team']));
}

// Ensure no unexpected output before sending JSON
$extra_output = trim(ob_get_clean());
if (!empty($extra_output)) {
    die(json_encode(['error' => 'Unexpected output detected: ' . $extra_output]));
}

echo json_encode(['status' => 'success']);
exit;

?>
