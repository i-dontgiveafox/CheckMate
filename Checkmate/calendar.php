<?php

  session_start();
    if (!isset($_SESSION['user_id'] )) {
    header("Location: login-signup.php");
    exit();
    header("Location: google-callback.php");
    exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/dashboard-style.css">
    <link rel="stylesheet" href="css/calendar.css">

    <link rel="shortcut icon" href="images/icon.png">

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title>Calendar</title>
</head>

<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="images/checkmate-logo-white.png" alt="logo">
                </span>

                <div class="text header-text">
                    <span class="name" onclick="location.href='user.php'">
                        <?= strtoupper(htmlspecialchars($_SESSION['user_name'])) ?>
                    </span>
                    <span class="profession" onclick="location.href='user.php'">
                        <?= htmlspecialchars($_SESSION['user_position'] ?? ' ') ?>
                    </span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <p class="menu-title">MENU</p>
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="dashboard.php">
                            <i class='bx bx-home-alt icon'></i>
                            <span class="text nav-text">Home</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="calendar.php" class="active">
                            <i class='bx bx-calendar icon'></i>
                            <span class="text nav-text">Calendar</span>
                        </a>
                    </li>


                    <li class="nav-link">
                        <a href="tasks.php">
                            <i class='bx bx-notepad icon'></i>
                            <span class="text nav-text">My Tasks</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="collab.php">
                            <i class='bx bxs-user icon'></i>
                            <span class="text nav-text">Collaborations</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href = "logout.php">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>
            </div>
        </div>
    </nav>

    <section class="home">
        <div class="home-content">
            <div class="title-text">Calendar</div>
            <div class="hello-container">
                <h1 class="text">Welcome back, <?= strtoupper(htmlspecialchars($_SESSION['user_name'])) ?>!</h1>
                <p class="current-date"></p>
            </div>

            <div class="container">
                <!-- Calendar -->
                <div class="calendar-container">
                    <div class="calendar-header">
                        <button id="prevMonth">&lt;</button>
                        <h2 id="monthYear"></h2>
                        <button id="nextMonth">&gt;</button>
                    </div>
                    <div class="day-names">
                        <div>Sun</div>
                        <div>Mon</div>
                        <div>Tue</div>
                        <div>Wed</div>
                        <div>Thu</div>
                        <div>Fri</div>
                        <div>Sat</div>
                    </div>
                    <div id="calendarGrid" class="calendar-grid"></div>
                </div>

                <!-- Event Sidebar -->
                <div class="event-container">
                    <h3 id="selectedDate">Tasks for </h3>
                    <ul id="eventList"></ul>

                    <input type="text" id="newEventInput" placeholder="Add new task">
                    <button id="addEventBtn">Add Task</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="javascript/dashboard-script.js"></script>
    <script src="javascript/calendar.js"></script>



</body>

</html>