<?php
include("connection.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login-signup.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_name = $_POST["task_name"];
    $team_id = $_POST["team_id"];
    $assigned_user_id = $_POST["assigned_user_id"];
    $priority = $_POST["priority"];
    $due_date = $_POST["due_date"];
    $status = "New"; // default status

    $stmt = $conn->prepare("INSERT INTO tasks (task_name, team_id, assigned_user_id, priority, due_date, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siisss", $task_name, $team_id, $assigned_user_id, $priority, $due_date, $status);

    if ($stmt->execute()) {
        header("Location: tasks.php?success=1");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
