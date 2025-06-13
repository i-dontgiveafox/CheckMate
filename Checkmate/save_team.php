<?php
header('Content-Type: application/json');
ob_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
$creator_id = $_SESSION['user_id'] ?? 1;

// DB connection
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

// Insert team
$stmt = $conn->prepare("INSERT INTO team (team_name, team_creator_id, team_color, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
$stmt->bind_param("sis", $team_name, $creator_id, $team_color);
if (!$stmt->execute()) {
    echo json_encode(['error' => 'Failed to insert team: ' . $stmt->error]);
    exit;
}
$team_id = $stmt->insert_id;
$stmt->close();

// Insert team members
$members = $_POST['members'] ?? [];
$member_data = [];

foreach ($members as $i => $user_id) {
    $member_id = intval($user_id);

    // Fetch full name
    $user_info = $conn->query("SELECT user_firstname, user_lastname FROM user WHERE user_id = $member_id")->fetch_assoc();
    $full_name = $user_info ? $user_info['user_firstname'] . ' ' . $user_info['user_lastname'] : 'Unknown';

    // Upload image
    $image_path = '';
    if (!empty($_FILES['member_images']['name'][$i]) && $_FILES['member_images']['error'][$i] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['member_images']['tmp_name'][$i];
        $original_name = basename($_FILES['member_images']['name'][$i]);
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $unique_name = uniqid() . '_' . $original_name;
        $image_path = $upload_dir . $unique_name;
        move_uploaded_file($tmp_name, $image_path);
    }

    // Insert member
    $stmt = $conn->prepare("INSERT INTO team_members (team_id, user_id, role, joined_at, name, image) VALUES (?, ?, 'member', NOW(), ?, ?)");
    $stmt->bind_param("iiss", $team_id, $member_id, $full_name, $image_path);
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Failed to insert member: ' . $stmt->error]);
        exit;
    }
    $stmt->close();

    // Push to response
    $member_data[] = [
        'name' => htmlspecialchars($full_name),
        'image' => $image_path
    ];
}

// Catch unexpected output
$extra = trim(ob_get_clean());
if (!empty($extra)) {
    echo json_encode(['error' => 'Unexpected output: ' . strip_tags($extra)]);
    exit;
}

// Final JSON
echo json_encode([
    'status' => 'success',
    'id' => $team_id,
    'name' => htmlspecialchars($team_name),
    'color' => $team_color,
    'members' => $member_data
]);
exit;
