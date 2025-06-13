<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$host = 'localhost';
$db = 'checkmate';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE user_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $token = bin2hex(random_bytes(50));
        $stmt = $conn->prepare("UPDATE user SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE user_email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        $resetLink = "http://localhost/checkmate/reset_password.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'deejaysarinas22@gmail.com';
            $mail->Password   = 'vrja mjnz xgdi msdu';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('deejaysarinas22@gmail.com', 'Checkmate App');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset';
            $mail->Body    = "Click the link to reset your password: <a href='$resetLink'>$resetLink</a>";

            $mail->send();
            $message = "Password reset link has been sent to your email.";
        } catch (Exception $e) {
            $message = "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "Email not found.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/forgot_password.css">

    <link rel="shortcut icon" href="images/icon.png">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">
    <div class="form-box login">
        <form method="POST">
            <h1>Forgot Password</h1>
            <p style="text-align:center; margin-bottom: 20px;">Enter your registered email to receive a reset link.</p>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <button type="submit" class="btn">Send Reset Link</button>
            <div class="forgot-link">
                <a href="login-signup.php">← Back to Login</a>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($message)): ?>
<script>
    Swal.fire({
        icon: '<?= (strpos(strtolower($message), "sent") !== false) ? "success" : "error" ?>',
        title: '<?= (strpos(strtolower($message), "sent") !== false) ? "Success!" : "Oops!" ?>',
        text: '<?= htmlspecialchars($message, ENT_QUOTES) ?>',
        confirmButtonColor: '#3085d6'
    });
</script>
<?php endif; ?>

</body>
</html>
