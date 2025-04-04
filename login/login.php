<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyMess - Hostel Mess Login</title>
    <link rel="stylesheet" href="login.css">
    <style>
    .links{
    justify-content: space-evenly;
    display: flex;
    padding-top: 15px;
    font-weight: bold;
    color: white;
  }
  button{
    color: #6695e4;
    border: none;
    background-color: transparent;
    font-size: 10 rem;
    font-weight: bold;
  }
  button:hover{
    color: #2165d9;
  }</style>
    <script>
        // Function to show success message
        window.onload = function() {
            <?php if(isset($_SESSION['registration_success'])): ?>
                alert("Registration Successful! ");
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
    <div class="login-container" id="sign" >
        <div class="login-background">
            <div class="login-card">
            <div class="logo-section">
                    <div class="logo">
                        <img src="../images/logo.png" alt="MyMess Logo"  />
                        <span >MyMess</span>
                    </div>
                </div>
                <div class="login-form">
                    <h2 >Sign up for the hostel mess of your choice:</h2>
                    
                    <form id="login-form" method="post" action="register.php">
                        
                        <input 
                            type="text" 
                            id="registrationNo"
                            placeholder="Registration No."
                            name="registrationNo" 
                            required    
                        />
                        <input 
                            type="email" 
                            id="email"
                            placeholder="email"
                            name="email" 
                            required 
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
                        <div class="links">
                            <p>Already have a account!</p>
                            <button id="loginin">Sign in</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
    <div class="login-container" id="login" >
        <div class="login-background">
            <div class="login-card">
                <div class="login-form">
                    <h2 >Sign up for the hostel mess of your choice:</h2>
                    
                    <form id="login-form" method="post" action="register.php">
                        
                        <input 
                            type="text" 
                            id="registrationNo"
                            placeholder="Registration No."
                            name="registrationNo" 
                            required 
                            
                        />
                        <input 
                            type="password" 
                            id="password"
                            placeholder="Password"
                            name="password" 
                            required 
                        />
                        <div class="recover">
                            <a href="forgot-password.php"  rel="noopener noreferrer">Forget Password</a>
                        </div>
                        <div id="error-message" class="error-message"></div>
                        <button type="submit" class="next-button" name="next-button">Next</button>
                        <div class="links">
                            <p>Didn't have a account yet?</p>
                            <button id="signin">Login</button>
                        </div>
                    </form>
                </div>
                <div class="logo-section">
                    <div class="logo">
                        <img src="../images/logo.png" alt="MyMess Logo"  />
                        <span >MyMess</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <script src="LOG.js"></script>
</body>
</html>