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

    <link rel="shortcut icon" href="images/icon.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Dashboard</title>
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
                <!--<li class="search-box">
                    <i class='bx bx-search icon'></i>
                        <input type="text" placeholder="Search...">
                </li>-->
                <p class="menu-title">MENU</p>
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="dashboard.php">
                            <i class='bx bx-home-alt icon'></i>
                            <span class="text nav-text">Home</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="calendar.php">
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
                    <a href="index.php">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>

                <!--<li class="mode">
                    <div class="moon-sun">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>

                    <span class="mode-text text">Dark Mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>-->

            </div>
        </div>
    </nav>

    <section class="home">
        <div class="home-content">
            <div class="title-text">Home</div>
            <div class="hello-container">
                <h1 class="text">Welcome back, <?= strtoupper(htmlspecialchars($_SESSION['user_name'])) ?>!</h1>
                <p class="current-date"></p>
            </div>

            <div class="card-container">
                <div class="card" id="card1">
                    <span class="card-title">Open</span>
                    <span class="card-values">20</span>
                    <span class="card-icon"><i class='bx bx-folder-open icon'></i></span>
                </div>
                <div class="card" id="card2">
                    <span class="card-title">Completed</span>
                    <span class="card-values">20</span>
                    <span class="card-icon"><i class='bx bx-check-circle icon'></i></span>
                </div>
                <div class="card" id="card3">
                    <span class="card-title">Overdue</span>
                    <span class="card-values">20</span>
                    <span class="card-icon"><i class='bx bx-alarm-exclamation icon'></i></span>
                </div>
                <div class="card" id="card4">
                    <span class="card-title">Due Today</span>
                    <span class="card-values">20</span>
                    <span class="card-icon"><i class='bx bx-alarm-exclamation icon'></i></span>
                </div>
            </div>

            <div class="dashboard-container">
                <div class="d-wrapper">
                    <h1 class="sub-title"><i class='bx bx-task'></i>&nbsp;&nbsp;To-Do List</h1>
                    <div class="todo-list">
                        <div class="todo-cards">
                            <div class="task-icon">
                                <i class='bx bx-code-alt task-icon' id="taskicon3"></i>
                            </div>
                            <div class="task-details">
                                <span class="task-title">Q1 Backend Development - Java Project</span>
                                <span class="task-description">Create a Wireframe and Low-Fi Prototype</span>

                                <!-- Three-dot Menu -->
                                <i class='bx bx-dots-vertical dot-menu' id="taskicon2"></i>

                                <!-- Members -->
                                <div class="members-container2">
                                    <span class="member-icon2" id="member1">A</span>
                                    <span class="member-icon2" id="member2">J</span>
                                    <span class="member-icon2" id="member3">+2</span>
                                </div>

                                <!-- Task Due Date -->
                                <div class="task-date" id="taskdate1">
                                    <i class='bx bx-time clock-icon'></i>Today
                                </div>
                            </div>
                        </div>

                        <div class="todo-cards">
                            <div class="task-icon">
                                <i class='bx bx-code-alt task-icon' id="taskicon3"></i>
                            </div>
                            <div class="task-details">
                                <span class="task-title">Midterm Portfolio - IT Elective</span>
                                <span class="task-description">Q2 Planning Phase: Code Structure</span>

                                <!-- Three-dot Menu -->
                                <i class='bx bx-dots-vertical dot-menu' id="taskicon2"></i>

                                <!-- Members -->
                                <div class="members-container2">
                                    <span class="member-icon2" id="member1">A</span>
                                    <span class="member-icon2" id="member2">J</span>
                                    <span class="member-icon2" id="member3">+2</span>
                                </div>

                                <!-- Task Due Date -->
                                <div class="task-date" id="taskdate1">
                                    <i class='bx bx-time clock-icon'></i>Today
                                </div>
                            </div>
                        </div>

                        <div class="todo-cards">
                            <div class="task-icon">
                                <i class='bx bx-code-alt task-icon' id="taskicon3"></i>
                            </div>
                            <div class="task-details">
                                <span class="task-title">Capstone Title Proposal</span>
                                <span class="task-description">Submit the proposed title and its documentation</span>

                                <!-- Three-dot Menu -->
                                <i class='bx bx-dots-vertical dot-menu' id="taskicon2"></i>

                                <!-- Members -->
                                <div class="members-container2">
                                    <span class="member-icon2" id="member1">A</span>
                                    <span class="member-icon2" id="member2">J</span>
                                    <span class="member-icon2" id="member3">+2</span>
                                </div>

                                <!-- Task Due Date -->
                                <div class="task-date" id="taskdate1">
                                    <i class='bx bx-time clock-icon'></i>Today
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="d-wrapper">
                    <h1 class="sub-title"><i class='bx bxs-folder-open'></i>&nbsp;&nbsp;Projects</h1>
                    <div class="todo-list">
                        <div class="todo-cards">
                            <div class="task-icon">
                                <i class='bx bx-globe task-icon id="taskicon2"'></i>
                            </div>
                            <div class="task-details">
                                <span class="task-title">Wed-Based POS System</span>
                                <span class="task-description">Created by: Anne A.</span>

                                <!-- Three-dot Menu -->
                                <i class='bx bx-dots-vertical dot-menu'></i>

                                <!-- Members -->
                                <div class="members-container">
                                    <span class="member-icon" id="member1">A</span>
                                    <span class="member-icon" id="member2">J</span>
                                    <span class="member-icon" id="member3">+2</span>
                                </div>
                            </div>
                        </div>
                        <div class="todo-cards">
                            <div class="task-icon">
                                <i class='bx bxs-book task-icon' id="taskicon1"></i>
                            </div>
                            <div class="task-details">
                                <span class="task-title">Book Keeping - Freelance Team 2024</span>
                                <span class="task-description">Created by: Anne A.</span>

                                <!-- Three-dot Menu -->
                                <i class='bx bx-dots-vertical dot-menu'></i>

                                <!-- Members -->
                                <div class="members-container">
                                    <span class="member-icon" id="member1">A</span>
                                    <span class="member-icon" id="member2">J</span>
                                    <span class="member-icon" id="member3">+2</span>
                                </div>
                            </div>
                        </div>
                        <div class="todo-cards">
                            <div class="task-icon">
                                <i class='bx bx-code-alt task-icon' id="taskicon3"></i>
                            </div>
                            <div class="task-details">
                                <span class="task-title">Q1 Backend Development - Java Project</span>
                                <span class="task-description">Created by: Anne A.</span>

                                <!-- Three-dot Menu -->
                                <i class='bx bx-dots-vertical dot-menu' id="taskicon2"></i>

                                <!-- Members -->
                                <div class="members-container">
                                    <span class="member-icon" id="member1">A</span>
                                    <span class="member-icon" id="member2">J</span>
                                    <span class="member-icon" id="member3">+2</span>
                                </div>
                            </div>
                        </div>
                        <div class="todo-cards">
                            <div class="task-icon">
                                <i class='bx bxs-data task-icon'></i>
                            </div>
                            <div class="task-details">
                                <span class="task-title">SQL Inventory System Project</span>
                                <span class="task-description">Created by: Anne A.</span>

                                <!-- Three-dot Menu -->
                                <i class='bx bx-dots-vertical dot-menu'></i>

                                <!-- Members -->
                                <div class="members-container">
                                    <span class="member-icon" id="member1">A</span>
                                    <span class="member-icon" id="member2">J</span>
                                    <span class="member-icon" id="member3">+2</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="javascript/dashboard-script.js"></script>
    <script>
        const today = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = today.toLocaleDateString('en-US', options);

        document.querySelector('.current-date').textContent = formattedDate;
    </script>


</body>

</html>