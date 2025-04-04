<?php
session_start();
include '../feed/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyMess - Dashboard</title>
    <style>*{
    margin: 0;
    padding: 0;
}
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #e3eaf1;
    color: #333;
    background-image: url('../images/Screenshot 2025-03-26 154233.png');
    background-size: cover;
}

.dashboard-container {
    max-width: auto;
    margin: 0 auto;
    padding: 0px;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #060000;
    color: white;
    padding: 15px;
    margin: 0;
    margin-bottom: 30px;
    padding-right: 40px;
    padding-left: 40px;
    justify-content: space-between;

    
}
.nav-links {
            display: flex;
            gap: 20px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .nav-links a:hover {
            color:rgb(40, 149, 217);
        }
.logo {
    display: flex;
    align-items: center;
}

.logo img {
    width: 50px;
    height: 50px;
    margin-right: 10px;
}

.logo span {
    font-size: 24px;
    font-weight: bold;
    padding-left: 20px;
    padding-left: 20px;
}

.header-right {
    position: relative;
}

.menu-icon {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: white;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(26, 24, 24, 0.2);
    z-index: 1;
    border-radius: 5px;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown-content a {
    color: #2196F3;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color:rgb(255, 255, 255);
}

main h1 {
    text-align: center;
    color:rgb(1, 15, 26);
    
}

.dashboard-grid {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-top: 30px;
}

.dashboard-item {
    text-align: center;
    background: linear-gradient(to bottom , #033aa8, #3170de,rgb(93, 170, 253),rgb(106, 187, 242), #78d7ff);
    
    color: white;
    border-radius: 50px;
    padding: 30px;
    width: 270px;
    height: 250px;
    transition: transform 0.3s ease;
    justify-content: space-evenly;
    box-shadow: 0 15px 15px rgba(21, 21, 23, 0.9);
}

.dashboard-item:hover {
    transform: scale(1.05);
}

.dashboard-item a {
    text-decoration: none;
    color: white;
}

.dashboard-item .icon {
    font-size: 64px;
    margin-top: 20px;
    align-items: center;
    justify-content: space-evenly;
}
.dashboard-item .icon1 {
    margin-top: 60px;
    align-items: center;
}
.dashboard-item h3 {
    margin: 0;
    font-size: 2rem;
}
h1{
    padding: 30px;
}</style>
</head>
<body>
<?php
if (isset($_SESSION['login_success_message'])) {
    echo "<div class='success-message'>" . $_SESSION['login_success_message'] . "</div>";
    // Unset the session message so it only shows once
    unset($_SESSION['login_success_message']);
}

// Check if there's any error message
if (isset($_SESSION['error_message'])) {
    echo "<div class='error-message'>" . $_SESSION['error_message'] . "</div>";
    unset($_SESSION['error_message']);
}?>
    <div class="dashboard-container">
        <header>
            <div class="logo">
                <img src="../images/logo.png" alt="MyMess Logo">
                <span>MyMess</span>
            </div>
            <div class="nav-links">
            <a href="../login/dashboard.php">Home</a>
            <a href="https://messit.vinnovateit.com/">Mess Menu</a>
            <a href="../next/rules.html" id="rules-link">Rules & Regulations</a>
            <a href="../feed/feedback.php" id="feedback-link">FeedBack</a>
        </div>
            <div class="header-right">
                <div class="dropdown">
                    <button class="menu-icon">☰</button>
                    <div class="dropdown-content">
                        <a href="#" id="profile-link">Profile</a>
                        <a href="#" id="settings-link">Settings</a>
                        <a href="../next/rules.html" id="rules-link">Rules & Regulations</a>
                        <a href="https://messit.vinnovateit.com/" id="menu-link">View Menu</a>
                        <a href="../feed/feedback.php" id="feedback-link">FeedBack</a>
                        <a href="../feed/admin_panel.php" id="admin_panel">report</a>
                        <a href="../logout.php" id="logout-link">Logout</a>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <h1>Welcome, MyMessUser!</h1>
            <div class="dashboard-grid">
                <div class="dashboard-item">
                    <a href="../next/rules.html" target="blank">
                        <div class="icon"><img src="../images/rule.png" alt="RULES" width="150px" height="100px"></div>
                        <h3>Rules & Regulations</h3>
                    </a>
                </div>
                <div class="dashboard-item">
                    <a href="https://messit.vinnovateit.com/" target="_blank">
                        <div class="icon1">
                            <img src="../images/rr.png" alt="MENU" width="250px" height="100px">
                        </div>
                        
                    </a>
                </div>
                <div class="dashboard-item">
                    <a href="../feed/feedback.php">
                        <div class="icon">
                            <img src="../images/feed.png" alt="feedback" width="150px" height="120px">
                        </div>
                        <h3>Feedback</h3>
                    </a>
                </div>
                <div class="dashboard-item">
                    <a href="../feed/admin_panel.php">
                        <div class="icon">
                            <img src="../images/r4.png" alt="admin" width="150px" height="115px">
                        </div>
                        <h3>Report</h3>
                    </a>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    // Check if user is logged in
    const token = localStorage.getItem('token');
    if (!token) {
        // Redirect to login if no token
       // window.location.href = 'login.html';
    }

    // Logout functionality
    const logoutLink = document.getElementById('logout-link');
    logoutLink.addEventListener('click', (e) => {
        e.preventDefault();
        // Remove token and redirect to login
        localStorage.removeItem('token');
      //  window.location.href = 'login.html';
    });

    // Profile link (placeholder)
    const profileLink = document.getElementById('profile-link');
    profileLink.addEventListener('click', (e) => {
        e.preventDefault();
        alert('Profile page coming soon!');
    });

    // Settings link (placeholder)
    const settingsLink = document.getElementById('settings-link');
    settingsLink.addEventListener('click', (e) => {
        e.preventDefault();
        alert('Settings page coming soon!');
    });
});
    </script>
</body>
</html>