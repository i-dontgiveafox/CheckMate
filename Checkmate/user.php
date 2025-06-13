<?php
session_start();
require 'config.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: index.php');
    exit();
}

// Extract user data
$first_name = $user['user_firstname'];
$last_name = $user['user_lastname'];
$email = $user['user_email'];
$contact = $user['user_contact'];
$birthdate = $user['user_bday'];
$position = $user['user_position'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>User Profile</title>
 
    <link rel="stylesheet" href="css/user-style.css">

    <link rel="shortcut icon" href="images/icon.png">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- SweetAlert2 for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <!-- ✅ ADD THIS HIDDEN INPUT FOR USER ID -->
    <input type="hidden" id="currentUserId" value="<?php echo $_SESSION['user_id']; ?>">

    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="images/checkmate-logo-white.png" alt="Checkmate logo">
                </span>
                <div class="text header-text">
                    <span class="name" onclick="location.href='user.php'"><?php echo strtoupper(htmlspecialchars($first_name)); ?></span>
                    <span class="profession" onclick="location.href='user.php'"><?php echo htmlspecialchars($position); ?></span>
                </div>
            </div>
            <i class="bx bx-chevron-right toggle"></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <p class="menu-title">MENU</p>
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
                    <a href="logout.php" class="logout">
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
                        <h2><?php echo strtoupper(htmlspecialchars($first_name . ' ' . $last_name. '')); ?></span>
                        <p><?php echo htmlspecialchars($position); ?></p>
                    </div>
                    <button class="edit-profile-btn" id="editProfileBtn">Edit Profile</button>
                </div>

                <div class="personal-info">
                    <div class="info-container">
                        <h3>Personal Information</h3>
                        <div class="info-group">
                            <div class="info-item">
                                <strong>First Name:</strong>
                                <span><?php echo htmlspecialchars($first_name); ?></span>
                            </div>
                            <div class="info-item">
                                <strong>Last Name:</strong>
                                <span><?php echo htmlspecialchars($last_name); ?></span>
                            </div>
                        </div>
                        <div class="info-group">
                             <div class="info-item">
                                <strong>Email Address:</strong>
                                <span><?php echo htmlspecialchars($email); ?></span>
                        </div>

                        <div class="info-item">
                                <strong>Phone Number:</strong>
                                <span><?php echo htmlspecialchars($contact); ?></span>
                        </div>
                    </div>

                        <div class="info-group">
                         <div class="info-item">
                          <strong>Birthdate:</strong>
                          <span><?php echo htmlspecialchars(date('F j, Y', strtotime($birthdate))); ?></span>
                        </div>
                    </div>
                    
                        <div class="joined-info">
                            <p><strong>Joined Date:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($user['created_at'] ?? 'now'))); ?></p>
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
            
            <!-- Email Confirmation Notification -->
            <div class="email-confirmation-notice" id="emailConfirmationNotice" style="display: none;">
                <div class="notice-content">
                    <i class="bx bx-info-circle notice-icon"></i>
                    <div class="notice-text">
                        <strong>Email Confirmation Required:</strong>
                        <span>Any changes to your profile will require email confirmation for security purposes. You will receive a confirmation link that expires in 30 minutes.</span>
                    </div>
                </div>
            </div>
            
            <form id="editForm">
                <input type="hidden" id="originalEmail" value="<?php echo htmlspecialchars($email); ?>">

                <div class="form-group">
                    <label for="firstName">First Name <span class="required">*</span>:</label>
                    <input type="text" id="firstName" value="<?php echo htmlspecialchars($first_name); ?>">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name <span class="required">*</span>:</label>
                    <input type="text" id="lastName" value="<?php echo htmlspecialchars($last_name); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span>:</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" value="<?php echo htmlspecialchars($contact); ?>">
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
    
    <script src="javascript/script-user.js" defer></script>
</body>

</html>