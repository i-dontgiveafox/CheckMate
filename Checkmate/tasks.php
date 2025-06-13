<?php
    include("connection.php");
    include("addtask.php");
    session_start();
    if (!isset($_SESSION['user_id'])) {
    header("Location: login-signup.php");
    exit();
    header("Location: google-callback.php");
    exit();
}

    $show_modal = $show_modal ?? false;

    // Set a test user ID (replace this with actual session logic in production)
    // $_SESSION["userID"] = 2551;
    $user_id = $_SESSION["user_id"];

    // Get selected team ID from GET parameter, default to 'all'
    $selected_team_id = $_GET['team_id'] ?? 'all';

    // Filtering condition setup
    $filter_condition = "";
    $params = [$user_id]; // Start with user ID
    $param_types = "i";   // 'i' for integer user ID

    if ($selected_team_id !== 'all') {
        $filter_condition = "AND tasks.team_id = ?";
        $params[] = $selected_team_id;
        $param_types .= "i";
    }

    // ---------- In Progress Tasks ----------
    $inprogress_sql = "SELECT 
        tasks.task_id, 
        tasks.task_name, 
        tasks.team_id, 
        tasks.status, 
        tasks.due_date, 
        tasks.priority, 
        team.team_name
    FROM tasks 
    INNER JOIN team ON tasks.team_id = team.team_id
    WHERE tasks.assigned_user_id = ? 
    AND tasks.status = 'In Progress'
    $filter_condition";

    $inprogress_stmt = $conn->prepare($inprogress_sql);
    $inprogress_stmt->bind_param($param_types, ...$params);
    $inprogress_stmt->execute();
    $inprogress_result = $inprogress_stmt->get_result();

    // ---------- New Tasks ----------
    $new_sql = "SELECT 
        tasks.task_id, 
        tasks.task_name, 
        tasks.team_id, 
        tasks.status, 
        tasks.due_date, 
        tasks.priority, 
        team.team_name
    FROM tasks 
    INNER JOIN team ON tasks.team_id = team.team_id
    WHERE tasks.assigned_user_id = ? 
    AND tasks.status = 'New'
    $filter_condition";

    $new_stmt = $conn->prepare($new_sql);
    $new_stmt->bind_param($param_types, ...$params);
    $new_stmt->execute();
    $new_result = $new_stmt->get_result();

    // ---------- Completed Tasks ----------
    $completed_sql = "SELECT 
        tasks.task_id, 
        tasks.task_name, 
        tasks.team_id, 
        tasks.status, 
        tasks.due_date, 
        tasks.priority, 
        team.team_name
    FROM tasks
    INNER JOIN team ON tasks.team_id = team.team_id
    WHERE tasks.assigned_user_id = ? 
    AND tasks.status = 'Completed'
    $filter_condition";

    $completed_stmt = $conn->prepare($completed_sql);
    $completed_stmt->bind_param($param_types, ...$params);
    $completed_stmt->execute();
    $completed_result = $completed_stmt->get_result();

    // ---------- Team Filter Dropdown ----------
    $taskfilter_sql = "SELECT 
        t.team_id, 
        t.team_name 
    FROM team t
    JOIN team_members tm ON t.team_id = tm.team_id
    WHERE tm.user_id = ?";

    $taskfilter_stmt = $conn->prepare($taskfilter_sql);
    $taskfilter_stmt->bind_param("i", $user_id);
    $taskfilter_stmt->execute();
    $result = $taskfilter_stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="images/icon.png">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style-task.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">

    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Boxicons CSS -->
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
                    <a href="logout.php">
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
            <div class="filter-wrapper">
                <form method="GET" action="tasks.php"> 
                    <label for="team_id">Team:</label>
                    <select name="team_id" id="team_id" onchange="this.form.submit()">
                        <option value="all">All</option>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <option value="<?= $row['team_id'] ?>" <?= ($selected_team_id == $row['team_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['team_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>
            </div>
            <div class="buttons-wrapper">
                <button class="add-task" id="add-task">
                    <span>Add Task</span>
                </button>



                <!-- <button class="manage-btn" id="manage-btn">
                    <span>Manage</span>
                </button> -->

                
            </div>

            <!-- Trigger Modal if Needed -->
            <?php if ($show_modal): ?>
            <script>
                window.onload = function() {
                    document.getElementById('addTaskModal').style.display = 'block';
                };
            </script>
            <?php endif; ?>

            <!-- Modal -->
            <div id="addTaskModal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close-btn" onclick="document.getElementById('addTaskModal').style.display='none'">&times;</span>
                    <h2 class="modal-title">Add New Task</h2>

                    <!-- Mini team selector form -->
                    <form method="POST">
                        <label for="selected_team">Team</label>
                        <select name="selected_team" onchange="this.form.submit()" class="form-control">
                            <option value="">Select a Team</option>
                            <?php
                            $teams = $conn->query("SELECT team_id, team_name FROM team ORDER BY team_id DESC");
                            while ($team = $teams->fetch_assoc()) {
                                $selected = ($team['team_id'] == $team_id) ? 'selected' : '';
                                echo "<option value='{$team['team_id']}' $selected>{$team['team_name']}</option>";
                            }
                            ?>
                        </select>
                    </form>

                    <!-- Full task form -->
                    <form method="POST">
                        <input type="hidden" name="team_id" value="<?php echo htmlspecialchars($team_id); ?>">

                        <label for="task_name">Task Name</label>
                        <input class="input-box" type="text" name="task_name" required>

                        <label for="assigned_user_id">Assign to</label>


                        <select name="assigned_user_id" required> 
                            <option value="">Select a Member</option>
                            <?php foreach ($team_members as $member): ?>
                                <option value="<?= $member['user_id'] ?>">
                                    <?= htmlspecialchars($member['user_firstname'] . ' ' . $member['user_lastname']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label for="task_description">Task Description</label>
                        <textarea class="input-box" name="task_description" required></textarea>

                        <label for="due_date">Due Date</label>
                        <input type="date" name="due_date" required>

                        <button type="submit" name="submit_task" value="1" class="submit-task">Add Task</button>
                    </form>
                </div>
            </div>

        </div>

        <center>
            <div class="home-content">

                <div class="flex-container">
                    <div class="table-box">
                        <div class="flex-title">New Task</div>
                            <?php
                            if (mysqli_num_rows($new_result) > 0) {
                                while ($row = mysqli_fetch_assoc($new_result)) {
                                    echo "<div class='card' id='card1'>";
                                    echo "<span class='task-title'>" . $row['task_name'] . "</span>";
                                    echo "<span class='task-description'>";
                                    echo "<i class='bx  bx-folder'></i>  " . $row['team_name'] . "</span>";

                                    $priority = strtolower($row['priority']); // make lowercase for consistency
                                    $prio_id = '';

                                    if ($priority === 'low') {
                                        $prio_id = 'lowprio';
                                    } elseif ($priority === 'medium') {
                                        $prio_id = 'mediumprio';
                                    } elseif ($priority === 'high') {
                                        $prio_id = 'highprio';
                                    } else {
                                        $prio_id = 'default-taskdate'; // fallback
                                    }

                                    echo "<div class='task-footer'>";
                                    echo "<div class='priority' id='$prio_id'>" . htmlspecialchars($row['priority']) . "</div>";
                                    echo "<div class='task-date'><i class='bx bx-time clock-icon'></i>" . date('F j', strtotime($row['due_date'])) . "</div>";
                                    echo "</div>";

                                    echo "<div class='dropdown'>
    <i class='bx bx-dots-vertical dot-menu' onclick='toggleDropdown(this)'></i>
    <div class='dropdown-content'>
        <a href='update-task.php?task_id=" . $row['task_id'] . "'>Update</a>
        <a href='delete-task.php?task_id=" . $row['task_id'] . "' onclick='return confirm(\"Are you sure you want to delete this task?\")'>Delete</a>
    </div>
</div>";
                                    echo "<div class='members-container'>";
                                    echo "</div></div>";
                                }
                            } else {
                                echo "<div class='card' id='card1'>";
                                echo "<p>No new tasks.</p>";
                                echo "</div>";
                            }
                            ?>
                    </div>

                    <div class="table-box">
                        <div class="flex-title">In Progress</div>
                            <?php
                            if (mysqli_num_rows($inprogress_result) > 0) {
                                while ($row = mysqli_fetch_assoc($inprogress_result)) {
                                    echo "<div class='card' id='card1'>";
                                    echo "<span class='task-title'>" . $row['task_name'] . "</span>";
                                    echo "<span class='task-description'>";
                                    echo "<i class='bx  bx-folder'></i>  " . $row['team_name'] . "</span>";

                                    $priority = strtolower($row['priority']); // make lowercase for consistency
                                    $prio_id = '';

                                    if ($priority === 'low') {
                                        $prio_id = 'lowprio';
                                    } elseif ($priority === 'medium') {
                                        $prio_id = 'mediumprio';
                                    } elseif ($priority === 'high') {
                                        $prio_id = 'highprio';
                                    } else {
                                        $prio_id = 'default-taskdate'; // fallback
                                    }

                                    echo "<div class='task-footer'>";
                                    echo "<div class='priority' id='$prio_id'>" . htmlspecialchars($row['priority']) . "</div>";
                                    echo "<div class='task-date'><i class='bx bx-time clock-icon'></i>" . date('F j', strtotime($row['due_date'])) . "</div>";
                                    echo "</div>";

                                    echo "<div class='dropdown'>
                                    <i class='bx bx-dots-vertical dot-menu' onclick='toggleDropdown(this)'></i>
                                    <div class='dropdown-content'>
                                        <a href='update-task.php?task_id=" . $row['task_id'] . "'>Update</a>
                                        <a href='delete-task.php?task_id=" . $row['task_id'] . "' onclick='return confirm(\"Are you sure you want to delete this task?\")'>Delete</a>
                                    </div>
                                    </div>";
                                    echo "<div class='members-container'>";
                                    echo "</div></div>";
                                }
                            } else {
                                echo "<div class='card' id='card1'>";
                                echo "<p>No new task.</p>";
                                echo "</div>";
                            }
                            ?>
                    </div>

                    <div class="table-box">
                        <div class="flex-title">Completed</div>
                        <?php
                            if (mysqli_num_rows($completed_result) > 0) {
                                while ($row = mysqli_fetch_assoc($completed_result)) {
                                    echo "<div class='card' id='card1'>";
                                    echo "<span class='task-title'>" . $row['task_name'] . "</span>";
                                    echo "<span class='task-description'>";
                                    echo "<i class='bx  bx-folder'></i>  " . $row['team_name'] . "</span>";

                                    $priority = strtolower($row['priority']); // make lowercase for consistency
                                    $prio_id = '';

                                    if ($priority === 'low') {
                                        $prio_id = 'lowprio';
                                    } elseif ($priority === 'medium') {
                                        $prio_id = 'mediumprio';
                                    } elseif ($priority === 'high') {
                                        $prio_id = 'highprio';
                                    } else {
                                        $prio_id = 'default-taskdate'; // fallback
                                    }

                                    echo "<div class='task-footer'>";
                                    echo "<div class='priority' id='$prio_id'>" . htmlspecialchars($row['priority']) . "</div>";
                                    echo "<div class='task-date'><i class='bx bx-time clock-icon'></i>" . date('F j', strtotime($row['due_date'])) . "</div>";
                                    echo "</div>";

                                    echo "<div class='dropdown'>
                                    <i class='bx bx-dots-vertical dot-menu' onclick='toggleDropdown(this)'></i>
                                    <div class='dropdown-content'>
                                        <a href='update-task.php?task_id=" . $row['task_id'] . "'>Update</a>
                                        <a href='delete-task.php?task_id=" . $row['task_id'] . "' onclick='return confirm(\"Are you sure you want to delete this task?\")'>Delete</a>
                                    </div>
                                    </div>";
                                    echo "<div class='members-container'>";
                                    echo "</div></div>";
                                }
                            } else {
                                echo "<div class='card' id='card1'>";
                                echo "<p>No completed task</p>";
                                echo "</div>";
                            }
                            ?>
                    </div>
                    <div id="taskDetailsModal" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close" onclick="closeTaskModal()">&times;</span>
    <h2 id="modalTaskTitle">Task Title</h2>

    <p><strong>Team:</strong> <span id="modalTaskTeam"></span></p>
    <p><strong>Description:</strong></p>
    <p id="modalTaskDescription"></p>
    <p><strong>Due Date:</strong> <span id="modalTaskDue"></span></p>
  </div>
</div>

                </div>
        </center>
    </section>

    <script src="javascript/dashboard-script.js"></script>
    <script>
function openTaskModal(taskTitle, taskDescription, taskDue, taskTeam) {
  document.getElementById("modalTaskTitle").textContent = taskTitle;
  document.getElementById("modalTaskDescription").textContent = taskDescription;
  document.getElementById("modalTaskDue").textContent = taskDue;
  document.getElementById("modalTaskTeam").textContent = taskTeam;

  document.getElementById("taskDetailsModal").style.display = "block";
}

function closeTaskModal() {
  document.getElementById("taskDetailsModal").style.display = "none";
}
</script>

    <script>
function toggleDropdown(element) {
    const dropdown = element.nextElementSibling;
    // Close all other dropdowns
    document.querySelectorAll('.dropdown-content').forEach(menu => {
        if (menu !== dropdown) menu.style.display = 'none';
    });
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

window.onclick = function(event) {
    if (!event.target.matches('.dot-menu')) {
        document.querySelectorAll('.dropdown-content').forEach(menu => {
            menu.style.display = 'none';
        });
    }
};
</script>


</body>

<script>
  const addTaskBtn = document.getElementById('add-task');
  const modal = document.getElementById('addTaskModal');
  const closeBtn = document.querySelector('.close-btn');

  addTaskBtn.addEventListener('click', () => {
    modal.style.display = 'block';
  });

  closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
  });

  window.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });
</script>


<script>
document.getElementById('teamFilter').addEventListener('change', function() {
    const teamId = this.value;

    fetch('filter_tasks.php?team_id=' + teamId)
        .then(response => response.text())
        .then(data => {
            document.querySelector('.table-box').innerHTML = data;
        });
});
</script>


</html>"