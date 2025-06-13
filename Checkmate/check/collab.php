<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Collaborations</title>
    <link rel="stylesheet" href="css/collab.css" />
    <link rel="stylesheet" href="css/collab-style.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  
<?php
$conn = new mysqli("localhost", "root", "", "checkmate");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$teams = $conn->query("SELECT * FROM team ORDER BY created_at ASC");
$members_raw = $conn->query("SELECT * FROM team_members");
$team_members = [];
while ($m = $members_raw->fetch_assoc()) {
    $team_members[$m['team_id']][] = [
        'name' => htmlspecialchars($m['name'] ?? $m['member_name']),
        'image' => htmlspecialchars($m['image'])
    ];
}
?>
<div class="wrapper">
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="images/checkmate-logo-white.png" alt="logo" />
                </span>
                <div class="text header-text" onclick="location.href='user.php'">
                    <span class="name">Anne A.</span>
                    <span class="profession">Web Developer</span>
                </div>
            </div>
            <i class='bx bx-chevron-right toggle'></i>
        </header>
        <div class="menu-bar">
            <div class="menu">
                <p class="menu-title">MENU</p>
                <ul class="menu-links">
                    <li class="nav-link"><a href="dashboard.php"><i class='bx bx-home-alt icon'></i><span class="text nav-text">Home</span></a></li>
                    <li class="nav-link"><a href="calendar.php"><i class='bx bx-calendar icon'></i><span class="text nav-text">Calendar</span></a></li>
                    <li class="nav-link"><a href="tasks.php"><i class='bx bx-notepad icon'></i><span class="text nav-text">My Tasks</span></a></li>
                    <li class="nav-link"><a href="collab.php"><i class='bx bxs-user icon'></i><span class="text nav-text">Collaborations</span></a></li>
                </ul>
            </div>
            <div class="bottom-content">
                <li><a href="index.php"><i class='bx bx-log-out icon'></i><span class="text nav-text">Logout</span></a></li>
            </div>
        </div>
    </nav>

    <div class="content">
        <h1>Projects</h1>
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search a Task or Schedule" />
        </div>

        <button id="openModalBtn" style="margin-bottom: 20px; padding: 10px 20px; background: #695CFE; color: white; border: none; border-radius: 8px; cursor: pointer;">
            + Create Group
        </button>

        <!-- CREATE TEAM MODAL -->
        <div id="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:9999;">
            <div style="background:white; padding:30px; border-radius:16px; width:100%; max-width:600px; box-shadow:0 8px 40px rgba(0,0,0,0.2); position:relative;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2 style="margin:0; font-size:24px;">Create New Team</h2>
                    <span id="closeModalBtn" style="cursor:pointer; font-size:24px;">&times;</span>
                </div>

                <form id="createTeamForm" method="POST" enctype="multipart/form-data">
                    <div style="display:flex; flex-direction:column; gap:15px;">
                        <label>
                            <span style="font-weight:bold;">Team Name</span>
                            <input type="text" name="team_name" placeholder="Enter team name" required style="padding:10px; border-radius:8px; border:1px solid #ccc; width:100%;">
                        </label>

                        <label>
                            <span style="font-weight:bold;">Team Color</span>
                            <select name="team_color" required style="padding:10px; border-radius:8px; border:1px solid #ccc; width:100%;">
                                <option value="">-- Select Color --</option>
                                <option value="#8e44ad">Purple</option>
                                <option value="#e74c3c">Red</option>
                                <option value="#27ae60">Green</option>
                                <option value="#3498db">Blue</option>
                            </select>
                        </label>

                        <div>
                            <span style="font-weight:bold;">Team Members</span>
                            <div id="memberContainer">
                                <div class="member-block" style="display:flex; gap:10px; margin-top:10px; align-items:center;">
                                    <input type="text" name="members[]" placeholder="Member Name" required style="flex:2; padding:10px; border-radius:8px; border:1px solid #ccc;">
                                    <input type="file" name="member_images[]" accept="image/*" required title="Upload profile image" style="flex:2;">
                                    <button type="button" class="removeMember" style="background:#e74c3c; color:white; border:none; padding:10px; border-radius:50%;">×</button>
                                </div>
                            </div>
                            <button type="button" id="addMemberBtn" style="margin-top:10px; background:#3498db; color:white; border:none; padding:10px 15px; border-radius:8px;">+ Add Member</button>
                        </div>

                        <button type="submit" id="saveGroupBtn" style="background-color:#8e44ad; color:white; padding:12px; border:none; border-radius:8px; font-weight:bold; margin-top:20px;">Save Team</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TEAM DISPLAY -->
        <div class="group-tasks">
            <h2 id="container-title">Group Tasks</h2>
            <?php while ($row = $teams->fetch_assoc()): ?>
                <div class="team-link">
                    <div class="team-container" data-id="<?= $row['team_id'] ?>" style="background: <?= htmlspecialchars($row['team_color'] ?? '#ccc') ?>;">
                        <div class="team-header" style="display:flex; justify-content:space-between;">
                            <h2><?= htmlspecialchars($row['team_name']) ?></h2>
                            <div style="display: flex; flex-direction: column; align-items: flex-end;">
                                <button 
                                    class="delete-team" 
                                    data-id="<?= $row['team_id'] ?>" 
                                    style="background: transparent; color: white; border: none; font-size: 18px; cursor: pointer; margin-bottom: 10px;"
                                    title="Delete team"
                                >✖</button>



                                <!-- teampage -->
                                <a 


                            
                                    href="#id=<?= $row['team_id'] ?>" 
                                    class="enter-team" 
                                    style="color:white; font-size: 18px; text-decoration: none;" 
                                    title="Open team"
                                >
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="team-members">
                            <?php if (!empty($team_members[$row['team_id']])): ?>
                              <?php foreach ($team_members[$row['team_id']] as $member): ?>
                                  <div class="member">
                                      <img src="<?= htmlspecialchars($member['image']) ?>" alt="<?= htmlspecialchars($member['name']) ?>" />
                                      <span><?= htmlspecialchars($member['name']) ?></span>
                                  </div>
                              <?php endforeach; ?>
                          <?php endif; ?>

                          <!-- Add Member Button -->
                          <div class="add-member-btn" data-team-id="<?= $row['team_id'] ?>" title="Add Member">
                              +
                          </div>


                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:10000;">
  <div style="background:#fff; padding:30px; border-radius:12px; text-align:center; box-shadow:0 0 10px rgba(0,0,0,0.25); width:90%; max-width:400px;">
    <p style="font-size:18px; margin-bottom:20px;">Are you sure you want to delete this team?</p>
    <div style="display:flex; justify-content:center; gap:20px;">
      <button id="confirmDelete" style="padding:10px 20px; background:#e74c3c; color:#fff; border:none; border-radius:6px; cursor:pointer;">Yes</button>
      <button id="cancelDelete" style="padding:10px 20px; background:#ccc; color:#000; border:none; border-radius:6px; cursor:pointer;">Cancel</button>
    </div>
  </div>
</div>

<script>
// Save scroll position on unload
window.addEventListener('beforeunload', () => {
    localStorage.setItem('scrollPosition', window.scrollY);
});

// Restore scroll position on load
window.addEventListener('load', () => {
    const scrollY = localStorage.getItem('scrollPosition');
    if (scrollY !== null) {
        window.scrollTo(0, parseInt(scrollY));
    }
});
</script>
<div id="addMemberModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:10000;">
  <div style="background:white; padding:30px; border-radius:12px; text-align:left; width:90%; max-width:400px;">
    <h3>Add Member</h3>
    <form id="addMemberForm" enctype="multipart/form-data">
      <input type="hidden" name="team_id" id="addTeamId">
      <div style="margin-bottom:15px;">
        <label>Name:</label>
        <input type="text" name="member_name" required style="width:100%; padding:8px;">
      </div>
      <div style="margin-bottom:15px;">
        <label>Profile Image:</label>
        <input type="file" name="member_image" accept="image/*" required>
      </div>
      <div style="display:flex; justify-content:flex-end; gap:10px;">
        <button type="button" id="cancelAddMember" style="padding:8px 16px;">Cancel</button>
        <button type="submit" id="addMemberSubmit" style="...">Add</button>

      </div>
    </form>
  </div>
</div>

<script src="javascript/collab.js?v=<?= time() ?>"></script>
</body>
</html>
