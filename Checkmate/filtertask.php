<?php
include 'connection.php';
session_start();

$user_id = $_SESSION['user_id'];
$team_id = $_GET['team_id'] ?? 'all';

if ($team_id === 'all') {
    $query = "SELECT * FROM tasks WHERE assigned_user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
} else {
    $query = "SELECT * FROM tasks WHERE assigned_user_id = ? AND project_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $team_id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card'>";
        echo "<i class='bx bxs-note task-icon'></i>";
        echo "<span class='task-title'>" . htmlspecialchars($row['task_name']) . "</span>";
        echo "<span class='task-description'>Project: " . htmlspecialchars($row['project_name']) . "</span>";
        echo "<div class='task-footer'>";
        echo "<div class='priority'>" . $row['priority'] . "</div>";
        echo "<div class='task-date'><i class='bx bx-time clock-icon'></i>" . date('F j', strtotime($row['due_date'])) . "</div>";
        echo "</div>";
        echo "<i class='bx bx-dots-vertical dot-menu'></i>";
        echo "<div class='members-container'>";
        echo "<span class='member-icon'>A</span>";
        echo "<span class='member-icon'>J</span>";
        echo "<span class='member-icon'>+2</span>";
        echo "</div></div>";
    }
} else {
    echo "<div class='card'><p>No tasks found.</p></div>";
}
