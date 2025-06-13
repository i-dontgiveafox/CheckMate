<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>User Profile</title>
 
    <link rel="stylesheet" href="css/user-styles.css">

    <link rel="shortcut icon" href="images/icon.png">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="images/checkmate-logo-white.png" alt="Checkmate logo">
                </span>
                <div class="text header-text">
                    <span class="name" onclick="location.href='user.php'">Anne A.</span>
                    <span class="profession" onclick="location.href='user.php'">Web Developer</span>
                </div>
            </div>
            <i class="bx bx-chevron-right toggle"></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="dashboard.php" data-section="Home">
                            <i class="bx bx-home-alt icon" aria-hidden="true"></i>
                            <span class="text nav-text">Home</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="calendar.php" data-section="Calendar">
                            <i class="bx bx-calendar icon" aria-hidden="true"></i>
                            <span class="text nav-text">Calendar</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="tasks.php" data-section="My Tasks">
                            <i class="bx bx-notepad icon" aria-hidden="true"></i>
                            <span class="text nav-text">My Tasks</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="collab.php" data-section="Collaborations">
                            <i class="bx bxs-user icon" aria-hidden="true"></i>
                            <span class="text nav-text">Collaborations</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="bottom-content">
                <li>
                    <a href="index.php" class="logout">
                        <i class="bx bx-log-out icon" aria-hidden="true"></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>
            </div>
        </div>
    </nav>

    <section class="home" id="mainContent">
        <div class="text">User Profile</div>
        <div class="main-content">
            <div class="profile-container">
                <div class="profile-header">
                    <img src="images/profile.jpg" alt="Profile Picture" class="profile-pic" id="profilePic">
                    <input type="file" id="fileInput" accept="image/*" style="display: none;" aria-hidden="true">
                    <div class="profile-info">
                        <h2>Anne A.</h2>
                        <p>Project Manager</p>
                        <p>Imus, Cavite</p>
                    </div>
                    <button class="edit-profile-btn" id="editProfileBtn">Edit Profile</button>
                </div>

                <div class="personal-info">
                    <div class="info-container">
                        <h3>Personal Information</h3>
                        <div class="info-group">
                            <div class="info-item">
                                <strong>First Name:</strong>
                                <span>Anne</span>
                            </div>
                            <div class="info-item">
                                <strong>Last Name:</strong>
                                <span>Asperilla</span>
                            </div>
                        </div>
                        <div class="info-group">
                            <div class="info-item">
                                <strong>Email Address:</strong>
                                <span>anne.asp@gmail.com</span>
                            </div>
                            <div class="info-item">
                                <strong>Phone Number:</strong>
                                <span>+63 912 345 6789</span>
                            </div>
                        </div>
                        <div class="joined-info">
                            <p><strong>Joined Date:</strong> April 20, 2024</p>
                        </div>
                        <button class="edit-info-btn" aria-label="Edit personal information">Edit</button>
                    </div>
                </div>

                <div class="tasks">
                    <div class="tasks-container">
                        <h3>My Tasks</h3>
                        <ul>
                            <li>
                                <div class="task-title-container">
                                    <span class="check-icon"><i class="bx bx-check" aria-hidden="true"></i></span>
                                    <span class="task-title">W1 Web Design</span>
                                </div>
                                <span class="task-details">Week 1 - Figma Submission</span>
                                <span class="status today">Today</span>
                            </li>
                            <li>
                                <div class="task-title-container">
                                    <span class="check-icon"><i class="bx bx-check" aria-hidden="true"></i></span>
                                    <span class="task-title">W1 Web Design</span>
                                </div>
                                <span class="task-details">Week 1 - Figma Submission</span>
                                <span class="status upcoming">Mar 5</span>
                            </li>
                            <li>
                                <div class="task-title-container">
                                    <span class="check-icon"><i class="bx bx-check" aria-hidden="true"></i></span>
                                    <span class="task-title">W1 Web Design</span>
                                </div>
                                <span class="task-details">Week 2 - Figma Submission</span>
                                <span class="status completed">Mar 12</span>
                            </li>
                        </ul>
                        <button class="view-tasks-btn" aria-label="View all tasks">View all tasks</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="popup" id="editPopup" role="dialog" aria-labelledby="editPopupTitle" aria-hidden="true">
        <div class="popup-content">
            <span class="close-btn" id="closePopup" aria-label="Close">&times;</span>
            <h3 id="editPopupTitle">Edit Personal Information</h3>
            <form id="editForm">
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" value="Anne">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" value="Asperilla">
                </div>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" value="anne.asp@gmail.com">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" value="+63 912 345 6789">
                </div>
                <button type="submit" class="save-btn">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Tasks Popup -->
    <div class="popup" id="tasksPopup" role="dialog" aria-labelledby="tasksPopupTitle" aria-hidden="true">
        <div class="popup-content tasks-popup-content">
            <span class="close-btn" id="closeTasksPopup" aria-label="Close">&times;</span>
            <h3 id="tasksPopupTitle">All Tasks</h3>
            <div class="tasks-list-container">
            </div>
        </div>
    </div>
    <script src="javascript/user-script.js" defer></script>
</body>

</html>