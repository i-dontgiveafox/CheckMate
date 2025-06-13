<?php
session_start();
include 'connection.php'; // your DB connection
if (!isset($_SESSION['user_id'])) {
    header("Location: login-signup.php");
    exit();
    header("Location: google-callback.php");
    exit();
}
if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = ?");
    $stmt->bind_param("i", $task_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Task deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete task.";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid task ID.";
}

$conn->close();
header("Location: tasks.php"); // Redirect back to task view
exit;
?>
