<?php
$conn = new mysqli("localhost", "root", "", "checkmate");

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    $member_id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM team_members WHERE member_id = ?");
    $stmt->bind_param("i", $member_id);
    if ($stmt->execute()) {
        echo "Member removed.";
    } else {
        http_response_code(500);
        echo "Error removing member.";
    }
}
?>
