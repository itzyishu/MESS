<?php
session_start();
require_once 'connect.php';

$message = '';
$error = '';
$validOTP = false;

// Check if user has verified OTP
if (!isset($_SESSION['verified_otp']) || !isset($_SESSION['reset_user_id'])) {
    // Redirect to forgot password page if no verified OTP
    header("Location: forgot-password.php");
    exit;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['reset_user_id'];
    $otp = $_SESSION['verified_otp'];
    
    // Re-verify OTP is still valid
    $verifyStmt = $conn->prepare("SELECT * FROM password_reset WHERE user_id = ? AND token = ? AND expires > NOW()");
    $verifyStmt->bind_param("is", $user_id, $otp);
    $verifyStmt->execute();
    $verifyResult = $verifyStmt->get_result();
    
    if ($verifyResult && $verifyResult->num_rows > 0) {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // Validate passwords
        if (empty($password) || empty($confirmPassword)) {
            $error = "Both password fields are required.";
        } elseif ($password !== $confirmPassword) {
            $error = "Passwords do not match.";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters long.";
        } else {
            // Use secure password hashing
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Update the user's password with prepared statement
            $updateStmt = $conn->prepare("UPDATE register SET password = ? WHERE id = ?");
            if (!$updateStmt) {
                $error = "Database error: " . $conn->error;
            } else {
                $updateStmt->bind_param("si", $hashed_password, $user_id);
                
                if ($updateStmt->execute()) {
                    // Delete used OTP with prepared statement
                    $deleteStmt = $conn->prepare("DELETE FROM password_reset WHERE user_id = ?");
                    $deleteStmt->bind_param("i", $user_id);
                    $deleteStmt->execute();
                    $deleteStmt->close();
                    
                    // Clear session variables
                    unset($_SESSION['reset_user_id']);
                    unset($_SESSION['verified_otp']);
                    unset($_SESSION['show_otp_form']);
                    
                    $message = "Your password has been reset successfully. You can now login with your new password.";
                    
                    // Auto-redirect after 5 seconds
                    header("refresh:5;url=login.php");
                } else {
                    $error = "Failed to update password: " . $updateStmt->error;
                }
                $updateStmt->close();
            }
        }
    } else {
        $error = "OTP verification failed or expired. Please try again.";
        // Clear session and redirect
        unset($_SESSION['reset_user_id']);
        unset($_SESSION['verified_otp']);
        unset($_SESSION['show_otp_form']);
        header("refresh:3;url=forgot-password.php");
    }
    $verifyStmt->close();
} else {
    // Verify OTP is still valid for viewing the page
    $user_id = $_SESSION['reset_user_id'];
    $otp = $_SESSION['verified_otp'];
    
    $checkStmt = $conn->prepare("SELECT * FROM password_reset WHERE user_id = ? AND token = ? AND expires > NOW()");
    $checkStmt->bind_param("is", $user_id, $otp);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if (!($checkResult && $checkResult->num_rows > 0)) {
        $error = "OTP verification failed or expired. Please try again.";
        // Clear session and redirect
        unset($_SESSION['reset_user_id']);
        unset($_SESSION['verified_otp']);
        unset($_SESSION['show_otp_form']);
        header("refresh:3;url=forgot-password.php");
    }
    $checkStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        small {
            display: block;
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
        
        .password-strength {
            margin-top: 5px;
            height: 5px;
            width: 100%;
            background: #ddd;
        }
        
        .password-strength span {
            display: block;
            height: 5px;
            width: 0%;
            transition: width 0.3s;
        }
        
        .weak {
            background-color: #ff4d4d;
        }
        
        .medium {
            background-color: #ffad4d;
        }
        
        .strong {
            background-color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" minlength="8" required>
                <div class="password-strength">
                    <span id="strength-bar"></span>
                </div>
                <small id="password-strength-text">Minimum 8 characters</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" minlength="8" required>
                <small id="password-match"></small>
            </div>
            
            <button type="submit">Reset Password</button>
        </form>
        
        <div class="back-link">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("form");
        const passwordInput = document.getElementById("password");
        const confirmInput = document.getElementById("confirm_password");
        const strengthBar = document.getElementById("strength-bar");
        const strengthText = document.getElementById("password-strength-text");
        const matchText = document.getElementById("password-match");
        
        // Check password strength
        passwordInput.addEventListener("input", function() {
            const password = passwordInput.value;
            let strength = 0;
            
            // Check length
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // Check for lowercase and uppercase
            if (password.match(/[a-z]+/)) strength += 1;
            if (password.match(/[A-Z]+/)) strength += 1;
            
            // Check for numbers and symbols
            if (password.match(/[0-9]+/)) strength += 1;
            if (password.match(/[^a-zA-Z0-9]+/)) strength += 1;
            
            // Update strength bar
            strengthBar.className = "";
            if (strength <= 2) {
                strengthBar.classList.add("weak");
                strengthText.textContent = "Weak password";
                strengthBar.style.width = "33%";
            } else if (strength <= 4) {
                strengthBar.classList.add("medium");
                strengthText.textContent = "Medium password";
                strengthBar.style.width = "67%";
            } else {
                strengthBar.classList.add("strong");
                strengthText.textContent = "Strong password";
                strengthBar.style.width = "100%";
            }
            
            // Check match
            checkPasswordMatch();
        });
        
        // Check if passwords match
        confirmInput.addEventListener("input", checkPasswordMatch);
        
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;
            
            if (confirmPassword === "") {
                matchText.textContent = "";
                return;
            }
            
            if (password === confirmPassword) {
                matchText.textContent = "Passwords match";
                matchText.style.color = "#155724";
            } else {
                matchText.textContent = "Passwords do not match";
                matchText.style.color = "#721c24";
            }
        }
        
        // Form validation
        form.addEventListener("submit", function(event) {
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;
            
            if (password !== confirmPassword) {
                event.preventDefault();
                alert("Passwords do not match!");
            }
        });
    });
    </script>
</body>
</html>