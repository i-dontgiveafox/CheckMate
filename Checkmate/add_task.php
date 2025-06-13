<?php
include("connection.php");
   session_start();
if (!isset($_SESSION['user_id'] )) {
    header("Location: login-signup.php");
    exit();
    header("Location: google-callback.php");
    exit();

}

function calculatePriority($due_date) {
    $today = new DateTime();
    $due = new DateTime($due_date);
    $interval = $today->diff($due)->days;

    if ($interval <= 2) return 'High';
    elseif ($interval <= 5) return 'Medium';
    else return 'Low';
}

// Modal control flags
$show_modal = false;
$team_id = $_POST['selected_team'] ?? null;
$team_members = [];

// Fetch team members if a team is selected
if ($team_id) {
    $show_modal = true; // tell PHP to reopen the modal
    $stmt = $conn->prepare("
        SELECT u.user_id, u.user_firstname, u.user_lastname
        FROM team_members tm
        JOIN user u ON tm.user_id = u.user_id
        WHERE tm.team_id = ?
    ");
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $team_members[] = $row;
    }
}

// Handle task submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_task'])) {
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $team_id = $_POST['team_id'];
    $assigned_user_id = $_POST['assigned_user_id'];
    $due_date = $_POST['due_date'];
    $status = 'New';

    $priority = calculatePriority($due_date);

    $stmt = $conn->prepare("INSERT INTO tasks (task_name, task_description, team_id, assigned_user_id, status, due_date, priority, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("ssiisss", $task_name, $task_description, $team_id, $assigned_user_id, $status, $due_date, $priority);

    if ($stmt->execute()) {
        header("Location: tasks.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
