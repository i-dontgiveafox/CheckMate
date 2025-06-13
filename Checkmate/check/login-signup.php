<?php
session_start();


require 'config.php';
$googleLoginUrl = $client->createAuthUrl();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $bday = $_POST['bday'];
        $contact = $_POST['contact'];
        $position = $_POST['position'];

        $stmt = $conn->prepare("INSERT INTO user (user_firstname, user_lastname, user_bday, user_contact, user_email, user_position, user_pass) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $fname, $lname, $bday, $contact, $email, $position, $password);

        if ($stmt->execute()) {
            $message = "Registration successful!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM user WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['user_pass'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['user_firstname'];
                $_SESSION['user_position'] = $user['user_position'];

                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "User not found. Try Again.";
        }
        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/SignUp_LogIn_Form.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="images/icon.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">
    <div class="form-box login">
        <form method="POST">
            <h1>Login</h1>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="forgot-link">
                <a href="forgot_password.php">Forgot Password?</a>
            </div>
            <button type="submit" name="login" class="btn">Login</button>
            <p>OR</p>
            <div class="social-icons">
                <a href="<?php echo htmlspecialchars($googleLoginUrl); ?>" class="google-btn"><i class='bx bxl-google'>Continue with google</i></a>
            </div>
        </form>
    </div>

    <div class="form-box register">
      <form method="POST" id="multiStepForm">
        <h1>Registration</h1>

        <div class="step step-1">
          <div class="input-box">
            <input type="text" name="firstname" placeholder="First Name" required>
            <i class='bx bxs-user'></i>
          </div>
          <div class="input-box">
            <input type="text" name="lastname" placeholder="Last Name" required>
            <i class='bx bxs-user'></i>
          </div>
          <div class="input-box">
            <input type="date" name="bday" placeholder="Birthday" required>
            <i class='bx bxs-calendar'></i>
          </div>
          <div class="input-box">
            <input type="text" name="contact" placeholder="Contact Number" required>
            <i class='bx bxs-phone'></i>
          </div>
          <button type="submit" class="btn" onclick="nextStep()">Next</button>
        </div>

        <div class="step step-2" style="display: none;">
          <div class="input-box">
            <input type="text" name="position" placeholder="Position" required>
            <i class='bx bxs-briefcase'></i>
          </div>
          <div class="input-box">
            <input type="email" name="email" placeholder="Email" required>
            <i class='bx bxs-envelope'></i>
          </div>
          <div class="input-box">
            <input type="password" name="password" id="password" placeholder="Password" required minlength="8" pattern=".{8,}" title="Password must be at least 8 characters">
            <i class='bx bxs-lock-alt'></i>
          </div>
          <div class="input-box">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required minlength="8" pattern=".{8,}" title="Password must be at least 8 characters">
            <i class='bx bxs-lock-alt'></i>
          </div>


          <div style="display: flex; justify-content: space-between; gap: 10px;">
            <button type="button" class="btn" onclick="previousStep()">Back</button>
            <button type="submit" name="register" class="btn">Register</button>
          </div>
        </div>
      </form>
    </div>
    <div class="toggle-box">
        <div class="toggle-panel toggle-left">
            <h1>Welcome Back!</h1>
            <p>Don't have an account?</p>
            <button class="btn register-btn">Register</button>
        </div>
        <div class="toggle-panel toggle-right">
            <h1>Hello, Welcome!</h1>
            <p>Already have an account?</p>
            <button class="btn login-btn">Login</button>
        </div>
    </div>
</div>

<script>
    function nextStep() {
  const currentStep = document.querySelector('.step-1');
  const inputs = currentStep.querySelectorAll('input');

  let isValid = true;

  inputs.forEach(input => {
    if (!input.checkValidity()) {
      input.reportValidity(); 
      isValid = false;
      return;
    }
  });

  if (isValid) {
    document.querySelector('.step-1').style.display = 'none';
    document.querySelector('.step-2').style.display = 'block';
  }
}


    function previousStep() {
        document.querySelector('.step-2').style.display = 'none';
        document.querySelector('.step-1').style.display = 'block';
    }

    if (window.location.hash === "#register") {
        document.querySelector('.container').classList.add('active-register');
    }
</script>

<script src="javascript/SignUp_LogIn_Form.js"></script>

<?php if (!empty($message)): ?>
<script>
    Swal.fire({
        icon: '<?= (strpos(strtolower($message), 'success') !== false) ? 'success' : 'error' ?>',
        title: '<?= (strpos(strtolower($message), 'success') !== false) ? 'Success!' : 'Oops!' ?>',
        text: '<?= htmlspecialchars($message, ENT_QUOTES) ?>',
        confirmButtonColor: '#3085d6'
    });
</script>
<?php endif; ?>

<script>
document.getElementById("multiStepForm").addEventListener("submit", function(e) {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;

    if (password !== confirmPassword) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: 'Passwords do not match.',
            confirmButtonColor: '#3085d6'
        });
    }
});
</script>


</body>
</html>
