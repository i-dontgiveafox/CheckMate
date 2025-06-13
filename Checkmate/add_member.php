<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_start();

$conn = new mysqli("localhost", "root", "", "checkmate");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_id = $_POST['team_id'] ?? '';
    $name = $_POST['member_name'] ?? '';
    $image = '';

    if (isset($_FILES['member_image']) && $_FILES['member_image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'uploads/';
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = uniqid() . '_' . basename($_FILES['member_image']['name']);
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['member_image']['tmp_name'], $targetPath)) {
            $image = $targetPath;
        } else {
            echo json_encode(['success' => false, 'error' => 'Upload failed']);
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO team_members (team_id, name, image) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $team_id, $name, $image);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'member' => [
                'id' => $stmt->insert_id,
                'name' => htmlspecialchars($name),
                'image' => $image
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Insert failed']);
    }
    exit;
}
?>
