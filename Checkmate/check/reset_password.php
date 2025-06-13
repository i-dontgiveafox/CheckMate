<?php
$host = 'localhost';
$db = 'checkmate';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

$message = "";
$token = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE user SET user_pass = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->bind_param("ss", $newPassword, $token);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $message = "Password has been reset successfully.";
    } else {
        $message = "Invalid or expired token.";
    }
} elseif (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    die("Invalid access.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/forgot_password.css">

    <link rel="shortcut icon" href="images/icon.png">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">
    <div class="form-box login">
        <form method="POST">
            <h1>Reset Password</h1>
            <p style="text-align:center; margin-bottom: 20px;">Enter your new password below.</p>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <div class="input-box">
                <input type="password" name="password"  placeholder="New Password" required minlength="8" pattern=".{8,}" title="Password must be at least 8 characters">
                <i class='bx bxs-lock-alt'></i>
            </div>
            <button type="submit" class="btn">Reset Password</button>
            <div class="forgot-link">
                <a href="login-signup.php">← Back to Login</a>
            </div>
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
            window.location.href = "login-signup.php";
        }
    });
</script>
<?php endif; ?>

</body>
</html>
