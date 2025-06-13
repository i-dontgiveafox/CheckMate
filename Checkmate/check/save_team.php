<?php
header('Content-Type: application/json');
ob_start(); // Start capturing output

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Use fallback if user is not logged in
$creator_id = $_SESSION['user_id'] ?? 1;

// Database connection
$conn = new mysqli("localhost", "root", "", "checkmate");
if ($conn->connect_error) {
    echo json_encode(['error' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

// Input validation
$team_name = trim($_POST['team_name'] ?? '');
$team_color = trim($_POST['team_color'] ?? '');

if (!$team_name || !$team_color) {
    echo json_encode(['error' => 'Missing team name or color']);
    exit;
}

// Insert new team
$stmt = $conn->prepare("INSERT INTO team (team_name, team_color, team_creator_id, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
$stmt->bind_param("ssi", $team_name, $team_color, $creator_id);

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Failed to insert team: ' . $stmt->error]);
    exit;
}
$team_id = $stmt->insert_id;
$stmt->close();

// Insert team members
$members = $_POST['members'] ?? [];
$member_data = [];

foreach ($members as $i => $member_name) {
    if (!$member_name) continue;

    $image_path = '';
    if (!empty($_FILES['member_images']['name'][$i]) && $_FILES['member_images']['error'][$i] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['member_images']['tmp_name'][$i];
        $original_name = basename($_FILES['member_images']['name'][$i]);
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $unique_name = uniqid() . '_' . $original_name;
        $image_path = $upload_dir . $unique_name;
        if (!move_uploaded_file($tmp_name, $image_path)) {
            echo json_encode(['error' => 'Image upload failed']);
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO team_members (team_id, user_id, role, joined_at, name, image) VALUES (?, ?, 'member', NOW(), ?, ?)");
    $stmt->bind_param("iiss", $team_id, $creator_id, $member_name, $image_path);
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Failed to insert member: ' . $stmt->error]);
        exit;
    }
    $stmt->close();

    $member_data[] = [
        'name' => htmlspecialchars($member_name),
        'image' => $image_path
    ];
}

// Catch any unwanted echoed content
$extra = trim(ob_get_clean());
if (!empty($extra)) {
    echo json_encode(['error' => 'Unexpected output: ' . strip_tags($extra)]);
    exit;
}

// Success response
echo json_encode([
    'status' => 'success',
    'id' => $team_id,
    'name' => htmlspecialchars($team_name),
    'color' => $team_color,
    'members' => $member_data
]);
exit;
