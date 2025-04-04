<?php
session_start();
require_once 'connect.php';

$message = '';
$error = '';
$validToken = false;
$tokenData = null;
$token = '';

// Check if token is provided
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM password_reset WHERE token = ? AND expires > NOW()");
    if (!$stmt) {
        $error = "Database error: " . $conn->error;
    } else {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $tokenData = $result->fetch_assoc();
            $validToken = true;
        } else {
            $error = "Invalid or expired password reset link. Please request a new one.";
        }
        $stmt->close();
    }
} else {
    $error = "No reset token provided.";
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Re-check token validity from POST
    if (isset($_POST['token']) && !empty($_POST['token'])) {
        $token = $_POST['token'];
        
        $checkStmt = $conn->prepare("SELECT * FROM password_reset WHERE token = ? AND expires > NOW()");
        $checkStmt->bind_param("s", $token);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult && $checkResult->num_rows > 0) {
            $tokenData = $checkResult->fetch_assoc();
            $validToken = true;
            
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
                    $updateStmt->bind_param("si", $hashed_password, $tokenData['user_id']);
                    
                    if ($updateStmt->execute()) {
                        // Delete used token with prepared statement
                        $deleteStmt = $conn->prepare("DELETE FROM password_reset WHERE token = ?");
                        $deleteStmt->bind_param("s", $token);
                        $deleteStmt->execute();
                        $deleteStmt->close();
                        
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
            $error = "Invalid or expired token.";
        }
        $checkStmt->close();
    } else {
        $error = "Token missing from form submission.";
    }
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
        
        <?php if ($validToken): ?>
            <form method="post" action="reset-password.php">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" minlength="8" required>
                    <small>Minimum 8 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" minlength="8" required>
                </div>
                
                <button type="submit">Reset Password</button>
            </form>
        <?php else: ?>
            <div class="back-link">
                <a href="forgot-password.php">Request New Reset Link</a>
            </div>
        <?php endif; ?>
        
        <div class="back-link">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
    
    <script>
    document.querySelector("form")?.addEventListener("submit", function(event) {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;
        
        if (password !== confirmPassword) {
            event.preventDefault();
            alert("Passwords do not match!");
        }
    });
    </script>
</body>
</html>