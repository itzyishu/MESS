<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyMess - Hostel Mess Login</title>
    <link rel="stylesheet" href="login.css">
    <script>
        // Function to show success message
        window.onload = function() {
            <?php if(isset($_SESSION['registration_success'])): ?>
                alert("Registration Successful! Please click ok and sign in once again.");
                <?php unset($_SESSION['registration_success']); ?>
            <?php endif; ?>

            <?php if(isset($_SESSION['login_success'])): ?>
                alert("Login Successful!");
                <?php unset($_SESSION['login_success']); ?>
            <?php endif; ?>

            <?php if(isset($_SESSION['error_message'])): ?>
                alert("<?php echo $_SESSION['error_message']; ?>");
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
        }
    </script>
</head>
<body>
    <div class="login-container">
        <div class="login-background">
            <div class="login-card">
                <div class="login-form">
                    <h2>Sign up for the hostel mess</h2>
                    
                    <form id="login-form" method="post" action="register.php">
                        <input 
                            type="text" 
                            id="registrationNo"
                            placeholder="Registration No."
                            name="registrationNo" 
                            required 
                            maxlength="9"
                        />
                        <input 
                            type="password" 
                            id="password"
                            placeholder="Password"
                            name="password" 
                            required 
                        />
                        <div id="error-message" class="error-message"></div>
                        <button type="submit" class="next-button" name="next-button">Next</button>
                    </form>
                </div>
                <div class="logo-section">
                    <div class="logo">
                        <img src="../images/logo.png" alt="MyMess Logo" />
                        <span>MyMess</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="LOG.js"></script>
</body>
</html>