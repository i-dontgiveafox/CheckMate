<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tasks-style.css">

    <link rel="shortcut icon" href="images/icon.png">

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Tasks</title>
</head>

<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="images/checkmate-logo-white.png" alt="logo">
                </span>

                <div class="text header-text">
                    <span class="name" onclick="location.href='user.php'">Anne A.</span>
                    <span class="profession" onclick="location.href='user.php'">Web Developer</span>
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
        <div class="title-text">My Tasks</div>
        <div class="header-wrapper">
            <div class="title-text2">Web Design Project
                <span class="title-text3">Task created by Anne A.</span>
            </div>
            <div class="buttons-wrapper">
                <button class="add-task" id="add-task">
                    <span>Add Task</span>
                </button>
                <button class="manage-btn" id="manage-btn">
                    <span>Manage</span>
                </button>
            </div>
        </div>

        <center>
            <div class="home-content">

                <div class="flex-container">
                    <div class="table-box">
                        <div class="flex-title">To-Do List</div>
                        <div class="card" id="card1">
                            <i class='bx bxs-note task-icon'></i>
                            <span class="task-title">W1 Web Design</span>
                            <span class="task-description">Week 1 - Figma</span>

                            <!-- Task Due Date -->
                            <div class="task-date" id="taskdate1">
                                <i class='bx bx-time clock-icon'></i>Today
                            </div>

                            <!-- Three-dot Menu -->
                            <i class='bx bx-dots-vertical dot-menu'></i>

                            <!-- Members -->
                            <div class="members-container">
                                <span class="member-icon" id="member1">A</span>
                                <span class="member-icon" id="member2">J</span>
                                <span class="member-icon" id="member3">+2</span>
                            </div>
                        </div>

                        <div class="card" id="card1">
                            <i class='bx bxs-note task-icon'></i>
                            <span class="task-title">W1 Web Design</span>
                            <span class="task-description">Week 1 - Figma</span>
                            <div class="task-date" id="taskdate1">
                                <i class='bx bx-time clock-icon'></i>Today
                            </div>

                            <!-- Three-dot Menu -->
                            <i class='bx bx-dots-vertical dot-menu'></i>
                            <!-- Members -->
                            <div class="members-container">
                                <span class="member-icon" id="member1">K</span>
                                <span class="member-icon" id="member2">J</span>
                                <span class="member-icon" id="member3">+4</span>
                            </div>
                        </div>

                        <div class="card" id="card1">
                            <i class='bx bxs-note task-icon'></i>
                            <span class="task-title">W1 Web Design</span>
                            <span class="task-description">Week 1 - Figma</span>
                            <div class="task-date" id="taskdate3">
                                <i class='bx bx-time clock-icon'></i>May 1
                            </div>

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

                    <div class="table-box">
                        <div class="flex-title">In Progress</div>
                        <div class="card" id="card1">
                            <i class='bx bxs-note task-icon'></i>
                            <span class="task-title">W1 Web Design</span>
                            <span class="task-description">Week 1 - Figma</span>
                            <!-- Task Due Date -->
                            <div class="task-date" id="taskdate3">
                                <i class='bx bx-time clock-icon'></i>Tomorrow
                            </div>

                            <!-- Three-dot Menu -->
                            <i class='bx bx-dots-vertical dot-menu'></i>

                            <!-- Members -->
                            <div class="members-container">
                                <span class="member-icon" id="member1">M</span>
                                <span class="member-icon" id="member2">K</span>
                                <span class="member-icon" id="member3">+1</span>
                            </div>
                        </div>

                        <div class="card" id="card1">
                            <i class='bx bxs-note task-icon'></i>
                            <span class="task-title">W1 Web Design</span>
                            <span class="task-description">Week 1 - Figma</span>
                            <!-- Task Due Date -->
                            <div class="task-date" id="taskdate2">
                                <i class='bx bx-time clock-icon'></i>May 4
                            </div>

                            <!-- Three-dot Menu -->
                            <i class='bx bx-dots-vertical dot-menu'></i>

                            <!-- Members -->
                            <div class="members-container">
                                <span class="member-icon" id="member1">J</span>
                                <span class="member-icon" id="member2">J</span>
                                <span class="member-icon" id="member3">+2</span>
                            </div>
                        </div>

                        <div class="card" id="card1">
                            <i class='bx bxs-note task-icon'></i>
                            <span class="task-title">W1 Web Design</span>
                            <span class="task-description">Week 1 - Figma</span>
                            <!-- Task Due Date -->
                            <div class="task-date" id="taskdate2">
                                <i class='bx bx-time clock-icon'></i>May 5
                            </div>

                            <!-- Three-dot Menu -->
                            <i class='bx bx-dots-vertical dot-menu'></i>

                            <!-- Members -->
                            <div class="members-container">
                                <span class="member-icon" id="member1">R</span>
                                <span class="member-icon" id="member2">A</span>
                                <span class="member-icon" id="member3">+5</span>
                            </div>
                        </div>

                        <div class="card" id="card1">
                            <i class='bx bxs-note task-icon'></i>
                            <span class="task-title">W1 Web Design</span>
                            <span class="task-description">Week 1 - Figma</span>
                            <!-- Task Due Date -->
                            <div class="task-date" id="taskdate3">
                                <i class='bx bx-time clock-icon'></i>Today
                            </div>

                            <!-- Three-dot Menu -->
                            <i class='bx bx-dots-vertical dot-menu'></i>

                            <!-- Members -->
                            <div class="members-container">
                                <span class="member-icon" id="member1">A</span>
                                <span class="member-icon" id="member2">K</span>
                                <span class="member-icon" id="member3">+8</span>
                            </div>
                        </div>

                        <div class="card" id="card1">
                            <i class='bx bxs-note task-icon'></i>
                            <span class="task-title">W1 Web Design</span>
                            <span class="task-description">Week 1 - Figma</span>
                            <!-- Task Due Date -->
                            <div class="task-date" id="taskdate3">
                                <i class='bx bx-time clock-icon'></i>Today
                            </div>

                            <!-- Three-dot Menu -->
                            <i class='bx bx-dots-vertical dot-menu'></i>

                            <!-- Members -->
                            <div class="members-container">
                                <span class="member-icon" id="member1">V</span>
                                <span class="member-icon" id="member2">J</span>
                                <span class="member-icon" id="member3">+2</span>
                            </div>
                        </div>


                    </div>

                    <div class="table-box">
                        <div class="flex-title">Completed</div>
                        <div class="card" id="card1">
                            <i class='bx bxs-note task-icon'></i>
                            <span class="task-title">W1 Web Design</span>
                            <span class="task-description">Week 1 - Figma</span>
                            <!-- Task Due Date -->
                            <div class="task-date" id="taskdate3">
                                <i class='bx bx-time clock-icon'></i>Today
                            </div>

                            <!-- Three-dot Menu -->
                            <i class='bx bx-dots-vertical dot-menu'></i>

                            <!-- Members -->
                            <div class="members-container">
                                <span class="member-icon" id="member1">J</span>
                                <span class="member-icon" id="member2">J</span>
                                <span class="member-icon" id="member3">+2</span>
                            </div>
                        </div>


                    </div>
                </div>
        </center>
    </section>

    <script src="javascript/dashboard-script.js"></script>

</body>

</html>