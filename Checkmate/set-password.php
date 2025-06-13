<?php 
session_start();
require 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: google-callback.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['password']) && strlen($_POST['password']) >= 8) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user_bday = $_POST['user_bday'] ?? null;
        $user_contact = $_POST['user_contact'] ?? null;
        $user_position = $_POST['user_position'] ?? null;
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("UPDATE user SET user_pass = ?, user_bday = ?, user_contact = ?, user_position = ? WHERE user_id = ?");
        $stmt->bind_param("ssssi", $password, $user_bday, $user_contact, $user_position, $user_id);

        if ($stmt->execute()) {
            $message = "Password and additional details have been set successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Password must be at least 8 characters long.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set Password</title>
    <link rel="stylesheet" href="css/forgot_password.css">
    <link rel="shortcut icon" href="images/icon.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">
    <div class="form-box login">
        <form method="POST">
            <h1>Set Password</h1>
            <p style="text-align:center; margin-bottom: 20px;">Choose your new password and fill in your details.</p>

            <div class="input-box">
                <input type="password" name="password" placeholder="New Password" required minlength="8" pattern=".{8,}" title="Password must be at least 8 characters">
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="input-box">
                <input type="date" name="user_bday" placeholder="Birthday" required>
                <i class='bx bxs-calendar'></i>
            </div>

            <div class="input-box">
                <input type="text" name="user_contact" placeholder="Contact Number" required>
                <i class='bx bxs-phone'></i>
            </div>

            <div class="input-box">
                <input type="text" name="user_position" placeholder="Position" required>
                <i class='bx bxs-briefcase'></i>
            </div>

            <button type="submit" class="btn">Set Password</button>
        </form>
    </div>
</div>

<?php if (!empty($message)): ?>
<script>
    const isSuccess = <?= (strpos(strtolower($message), "successfully") !== false) ? "true" : "false" ?>;
    Swal.fire({
        icon: isSuccess ? "success" : "error",
        title: isSuccess ? "Success!" : "Oops!",
        text: '<?= htmlspecialchars($message, ENT_QUOTES) ?>',
        confirmButtonColor: '#3085d6'
    }).then((result) => {
        if (isSuccess) {
            window.location.href = "dashboard.php";
        }
    });
</script>
<?php endif; ?>
</body>
</html>
