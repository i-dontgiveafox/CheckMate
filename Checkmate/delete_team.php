<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "checkmate");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$team_id = $_GET['id'] ?? null;
if (!$team_id || !is_numeric($team_id)) {
    echo json_encode(['error' => 'Invalid team ID']);
    exit;
}

// Delete members first to satisfy foreign key constraint
$stmt1 = $conn->prepare("DELETE FROM team_members WHERE team_id = ?");
$stmt1->bind_param("i", $team_id);
$stmt1->execute();
$stmt1->close();

// Then delete the team
$stmt2 = $conn->prepare("DELETE FROM team WHERE team_id = ?");
$stmt2->bind_param("i", $team_id);
$stmt2->execute();
$stmt2->close();

echo json_encode(['status' => 'success']);
exit;
?>
