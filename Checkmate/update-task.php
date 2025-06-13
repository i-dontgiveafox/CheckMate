<?php
session_start();
include 'connection.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login-signup.php");
    exit();
    header("Location: google-callback.php");
    exit();
}
if (!isset($_GET['task_id'])) {
    $_SESSION['error'] = "No task ID provided.";
    header("Location: tasks.php");
    exit;
}

$task_id = $_GET['task_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $due_date = $_POST['due_date'];

    $stmt = $conn->prepare("UPDATE tasks SET task_name = ?, task_description = ?, due_date = ? WHERE task_id = ?");
    $stmt->bind_param("sssi", $title, $desc, $due_date, $task_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Task updated successfully.";
        header("Location: tasks.php");
        exit;
    } else {
        $_SESSION['error'] = "Update failed.";
    }

    $stmt->close();
}

// Fetch task data
$stmt = $conn->prepare("SELECT * FROM tasks WHERE task_id = ?");
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Task not found.";
    header("Location: tasks.php");
    exit;
}

$task = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Task</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style-update.css">
</head>
<body>
    <div class="update-task-container">
        <span class="close-btn" onclick="window.location.href='tasks.php'">&times;</span>
        <h2>Update Task</h2>
        <form method="POST">
            <label>Task Name</label>
            <input type="text" name="title" value="<?= htmlspecialchars($task['task_name']) ?>" required>
            
            <label>Task Description</label>
            <textarea name="description" required><?= htmlspecialchars($task['task_description']) ?></textarea>
            
            <label>Due Date</label>
            <input type="date" name="due_date" value="<?= htmlspecialchars($task['due_date']) ?>" required>
            
            <button type="submit">Update Task</button>
        </form>
    </div>
</body>
</html>

