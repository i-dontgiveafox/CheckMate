<?php
    include("connection.php");
    session_start();
    if (!isset($_SESSION['user_id'] )) {
    header("Location: login-signup.php");
    exit();
    header("Location: google-callback.php");
    exit();

}

    $user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="css/dashboard-style.css">
 
        <link rel="shortcut icon" href="images/icon.png">
    <!-- Boxicons CSS -->
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
                <h1 class="text">Welcome, <?= strtoupper(htmlspecialchars($_SESSION['user_name'])) ?>!</h1>
                <p class="current-date"></p>
            </div>

            <div class="card-container">
                <!-- Fetch total count of tasks assigned to the user -->
                <?php
                    $totaltask_sql = "SELECT COUNT(*) AS total_tasks FROM tasks WHERE assigned_user_id = ?";
                    $totaltask_stmt = $conn->prepare($totaltask_sql);
                    $totaltask_stmt->bind_param("i", $user_id);
                    $totaltask_stmt->execute();
                    $totaltask_result = $totaltask_stmt->get_result();
                    $row = $totaltask_result->fetch_assoc();
                    $totalTasks = $row['total_tasks'];
                ?>
                <div class="card" id="card1">
                    <span class="card-title">Total Tasks</span>
                    <span class="card-values"><?php echo $totalTasks; ?></span>
                    <span class="card-icon"><i class='bx bx-alarm-exclamation icon'></i></span>
                </div>
                <!-- Fetch total count of new tasks assigned to the user -->
                <?php
                    $newtask_sql = "SELECT COUNT(*) AS new_tasks FROM tasks WHERE assigned_user_id = ?  AND status = 'New'";
                    $newtask_stmt = $conn->prepare($newtask_sql);
                    $newtask_stmt->bind_param("i", $user_id);
                    $newtask_stmt->execute();
                    $newtask_result = $newtask_stmt->get_result();
                    $row = $newtask_result->fetch_assoc();
                    $newTasks = $row['new_tasks'];
                ?>
                <div class="card" id="card2">
                    <span class="card-title">New</span>
                    <span class="card-values"><?php echo $newTasks; ?></span>
                    <span class="card-icon"><i class='bx bx-folder-open icon'></i></span>
                </div>

                <!-- Fetch total count of new tasks assigned to the user -->
                <?php
                    $ip_sql = "SELECT COUNT(*) AS inprogress_tasks FROM tasks WHERE assigned_user_id = ?  AND status = 'In Progress'";
                    $ip_smt = $conn->prepare($ip_sql);
                    $ip_smt->bind_param("i", $user_id);
                    $ip_smt->execute();
                    $ip_result = $ip_smt->get_result();
                    $row = $ip_result->fetch_assoc();
                    $ipTasks = $row['inprogress_tasks'];
                ?>
                <div class="card" id="card3">
                    <span class="card-title">In Progress</span>
                    <span class="card-values"><?php echo $ipTasks; ?></span>
                    <span class="card-icon"><i class='bx bx-check-circle icon'></i></span>
                </div>

                <!-- Fetch total count of completed tasks assigned to the user -->
                <?php
                    $completed_sql = "SELECT COUNT(*) AS completed_tasks FROM tasks WHERE assigned_user_id = ?  AND status = 'Completed'";
                    $completed_smt = $conn->prepare($completed_sql);
                    $completed_smt->bind_param("i", $user_id);
                    $completed_smt->execute();
                    $completed_result = $completed_smt->get_result();
                    $row = $completed_result->fetch_assoc();
                    $completedTasks = $row['completed_tasks'];
                ?>
                <div class="card" id="card4">
                    <span class="card-title">Completed</span>
                    <span class="card-values"><?php echo $completedTasks; ?></span>
                    <span class="card-icon"><i class='bx bx-alarm-exclamation icon'></i></span>
                </div>

                <!-- Fetch total count of overdue tasks assigned to the user -->
                <?php
                    $completed_sql = "SELECT COUNT(*) AS completed_tasks FROM tasks WHERE assigned_user_id = ?  AND status = 'Completed'";
                    $completed_smt = $conn->prepare($completed_sql);
                    $completed_smt->bind_param("i", $user_id);
                    $completed_smt->execute();
                    $completed_result = $completed_smt->get_result();
                    $row = $completed_result->fetch_assoc();
                    $completedTasks = $row['completed_tasks'];
                ?>
                
            </div>

            <?php
                // Prepare the query (correct SQL syntax)
                $todo_sql = "
                    SELECT t.task_name, t.task_description, t.due_date, tm.team_name
                    FROM tasks t
                    JOIN team tm ON t.team_id = tm.team_id
                    WHERE t.assigned_user_id = ?
                ";
                
                $todo_stmt = $conn->prepare($todo_sql);
                $todo_stmt->bind_param("i", $user_id);
                $todo_stmt->execute();
                $todo_result = $todo_stmt->get_result();

            ?>

            <div class="dashboard-container">
                <div class="d-wrapper">
                    <h1 class="sub-title"><i class='bx bx-task'></i>&nbsp;&nbsp;To-Do List</h1>
                    <div class="todo-list">
                        <?php if ($todo_result->num_rows > 0): ?>
                            <?php while ($task = $todo_result->fetch_assoc()): ?>
                                <div class="todo-cards">
                                    <!--<div class="task-icon">
                                        <i class='bx bx-code-alt task-icon'></i>
                                    </div>-->
                                    <div class="task-details">
                                        <span class="task-title">
                                            <?= htmlspecialchars($task['task_name']) ?>
                                            <span style="margin-left: 10px; font-weight: normal; color: black; font-size: 0.9em;">
                                                <i class='bx bx-folder'></i>
                                                <?= htmlspecialchars($task['team_name']) ?>
                                            </span>
                                        </span>
                                        <span class="task-description"><?php echo htmlspecialchars($task['task_description']); ?></span>

                                        <i class='bx bx-dots-vertical dot-menu'></i>

                                        <!-- Members (static for now) 
                                        <div class="members-container2">
                                            <span class="member-icon2">A</span>
                                            <span class="member-icon2">J</span>
                                            <span class="member-icon2">+2</span>
                                        </div>-->

                                        <div class="task-date">
                                            <i class='bx bx-time clock-icon'></i>
                                            <?php echo date("M d, Y", strtotime($task['due_date'])); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No high-priority tasks found.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
                // Fetch teams and their creators
                $team_sql = "
                    SELECT t.team_id, t.team_name, t.team_creator_id, u.user_firstname, u.user_lastname
                    FROM team t
                    JOIN team_members tm ON t.team_id = tm.team_id
                    JOIN user u ON t.team_creator_id = u.user_id
                    WHERE tm.user_id = ?
                ";
                $team_stmt = $conn->prepare($team_sql);
                $team_stmt->bind_param("i", $user_id);
                $team_stmt->execute();
                $team_result = $team_stmt->get_result();
                ?>

                <div class="d-wrapper">
                    <h1 class="sub-title"><i class='bx bxs-folder-open'></i>&nbsp;&nbsp;Teams</h1>
                    <div class="todo-list">
                        <?php while ($team = $team_result->fetch_assoc()): ?>
                            <div class="todo-cards">
                                <!--<div class="task-icon">
                                    <i class='bx bx-globe task-icon' id="taskicon2"></i>
                                </div>-->
                                <div class="task-details">
                                    <span class="task-title"><?= htmlspecialchars($team['team_name']) ?></span>
                                    <span class="task-description">
                                        Created by: <?= htmlspecialchars($team['user_firstname'] . ' ' . $team['user_lastname']) ?>
                                    </span>

                                    <!-- Three-dot Menu -->
                                    <i class='bx bx-dots-vertical dot-menu'></i>

                                    <!-- Members -->
                                    <div class="members-container">
                                        <?php
                                        // Fetch up to 3 members for initials display
                                        $member_sql = "
                                            SELECT u.user_firstname, u.user_lastname
                                            FROM team_members tm
                                            JOIN user u ON tm.user_id = u.user_id
                                            WHERE tm.team_id = ?
                                            LIMIT 3
                                        ";
                                        $member_stmt = $conn->prepare($member_sql);
                                        $member_stmt->bind_param("i", $team['team_id']);
                                        $member_stmt->execute();
                                        $member_result = $member_stmt->get_result();
                                        $member_initials = [];

                                        while ($member = $member_result->fetch_assoc()) {
                                            $first = strtoupper(substr($member['user_firstname'], 0, 1));
                                            $last = strtoupper(substr($member['user_lastname'], 0, 1));
                                            $initials = $first . $last;
                                            $member_initials[] = $initials;
                                        }

                                        foreach ($member_initials as $initial) {
                                            echo "<span class='member-icon'>" . $initial . "</span>";
                                        }

                                        // Show "+X" if more than 3 members
                                        $count_sql = "SELECT COUNT(*) as total FROM team_members WHERE team_id = ?";
                                        $count_stmt = $conn->prepare($count_sql);
                                        $count_stmt->bind_param("i", $team['team_id']);
                                        $count_stmt->execute();
                                        $count_result = $count_stmt->get_result()->fetch_assoc();
                                        $total_members = $count_result['total'];
                                        $extra = $total_members - count($member_initials);
                                        if ($extra > 0) {
                                            echo "<span class='member-icon'>+{$extra}</span>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
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