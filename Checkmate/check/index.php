<?php
session_start();
session_destroy();
header("Location: login-signup.php");
exit();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckMate</title>
    <link rel="stylesheet" href="css/index.css">

    <link rel="shortcut icon" href="images/icon.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-default navbar-fixed-top navbar-sticky-function">
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i style="font-size:24px" class="fa">&#xf0c9;</i>
        </label>
        <label class="logo">CheckMate</label>
        <ul>
            <li><a class="login" href="login-signup.php">Login</a></li>
            <li><a class="active" href="login-signup.php#register">Get Started</a></li>
        </ul>
    </nav>
    <div class="heading">
        <div class="title">
            <h1>CheckMate</h1>
            <p>The Ultimate Task Management tool for individuals and teams</p>
            <div class="description">
                <div class="container">
                    Simple.<br>
                    Intuitive.<br>
                    Efficient.
                </div>
            </div>
            <button class="title-btn"> How does it work?</button>
        </div>
    </div>

    <div class="heading2">
        <div class="title2">
            <h1>Why choose Checkmate?</h1>
            <h2>In a world where productivity matters,
                CheckMate stands out as the ultimate task management tool designed for both individuals and teams.
                Here’s why users choose CheckMate over the competition:</h2>

            <div class="milestone-card">
                <div class="feature">
                    <img src="images/milestone.jpeg">
                    <div class="introduction">Effortless Task Management</div>
                    <div class="description">
                        Creating, assigning, and tracking tasks has never been easier.
                    </div>
                </div>

                <div class="feature">
                    <img src="images/calendar.jpeg">
                    <div class="introduction">Stay on Schedule</div>
                    <div class="description">
                        Never miss a deadline with automated reminders, priority settings, and progress tracking,
                        keeping your projects on course.
                    </div>
                </div>

                <div class="feature">
                    <img src="images/image3.jpg">
                    <div class="introduction">Smart and Reliable</div>
                    <div class="description">
                        With its efficient features and user-friendly design, CheckMate eliminates task clutter,
                        enhances focus, and boosts overall productivity.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="heading3">
        <div class="title3">
            <h1>How does CheckMate works?</h1>
            <div class="steps">
                <div class="step">
                    <img src="images/user.png">
                    <h3>Login to an account</h3>
                    <p>Allows you to securely access your tasks and profile by entering your registered email and
                        password.</p>
                </div>
                <div class="step">
                    <img src="images/taskadd.png">
                    <h3>Add a task</h3>
                    <p>Users can create a new task by entering a title, description, due date, and priority level.</p>
                </div>
                <div class="step">
                    <img src="images/assign.png">
                    <h3>Assign task</h3>
                    <p>Tasks can be assigned to team members, categorized with tags, and tracked for progress.</p>
                </div>
                <div class="step">
                    <img src="images/user-progress.png">
                    <h3>Track Progress</h3>
                    <p>Stay on top of deadlines, collaborate with team members, and ensure projects stay on track.</p>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="footer-content">
            <div class="left">
                <img src="images/checkmate-logo.png" class="logo">
                <span id="scrollTop" onclick="scrollToTop()">Return at the top</span>
            </div>
            <div class="right">
                <a href="#">Register</a> | <a href="login-signup.php">Login</a>
            </div>
        </div>
        <div class="copyright">
            © CheckMate 2025
        </div>
    </footer>

    <script src="javascript/index.js"></script>

</body>

</html>