<?php
session_start();
require_once 'connect.php';

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registrationNo = $_POST['registrationNo'];
    
    // Validate input
    if (empty($registrationNo)) {
        $error = "Registration Number cannot be empty";
    } else {
        // Server-side validation for registration number format
        if (strlen($registrationNo) !== 9 || 
            !ctype_digit(substr($registrationNo, 0, 2)) || 
            !ctype_alpha(substr($registrationNo, 2, 3)) || 
            !ctype_digit(substr($registrationNo, 5, 4))) {
            $error = "Invalid Registration Number format";
        } else {
            // Use prepared statements to prevent SQL injection
            $stmt = $conn->prepare("SELECT id, email FROM register WHERE registrationNo = ?");
            if (!$stmt) {
                $error = "Database error: " . $conn->error;
            } else {
                $stmt->bind_param("s", $registrationNo);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result && $result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    
                    // Check if email exists for this user
                    if (empty($user['email'])) {
                        $error = "No email associated with this registration number. Please contact administrator.";
                    } else {
                        // Generate reset token
                        $token = bin2hex(random_bytes(32));
                        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry
                        
                        // Create password_reset table if not exists
                        $createTable = "CREATE TABLE IF NOT EXISTS password_reset (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            user_id INT NOT NULL,
                            token VARCHAR(64) NOT NULL,
                            expires DATETIME NOT NULL,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            INDEX(token),
                            INDEX(user_id)
                        )";
                        
                        if (!$conn->query($createTable)) {
                            $error = "Failed to create password reset table: " . $conn->error;
                        } else {
                            // Delete any existing tokens for this user
                            $deleteStmt = $conn->prepare("DELETE FROM password_reset WHERE user_id = ?");
                            $deleteStmt->bind_param("i", $user['id']);
                            $deleteStmt->execute();
                            $deleteStmt->close();
                            
                            // Insert new token using prepared statement
                            $insertStmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) VALUES (?, ?, ?)");
                            $insertStmt->bind_param("iss", $user['id'], $token, $expires);
                            
                            if ($insertStmt->execute()) {
                                // CHANGED: Simplify reset link generation
                                $resetLink = "reset-password.php?token=$token";
                                
                                // DEVELOPMENT MODE: Skip email sending and display the link directly
                                $message = "<strong>Development Mode:</strong> Password reset link generated.<br>";
                                $message .= "User Email: " . htmlspecialchars($user['email']) . "<br>";
                                $message .= "<a href='$resetLink' target='_blank'>Click here to reset password</a>";
                                
                                // Optional: Log the reset info to a file
                                $logFile = __DIR__ . '/password_resets.log';
                                $logEntry = date('Y-m-d H:i:s') . " - Reset for: " . $user['email'] . " - Token: " . $token . "\n";
                                file_put_contents($logFile, $logEntry, FILE_APPEND);
                                
                                // PRODUCTION CODE (commented out for now):
                                /*
                                $to = $user['email'];
                                $subject = "Password Reset Request";
                                $messageBody = "
                                    <html>
                                    <head>
                                        <title>Password Reset</title>
                                    </head>
                                    <body>
                                        <p>Hello,</p>
                                        <p>We received a request to reset your password. Click the link below to reset it:</p>
                                        <p><a href='$resetLink'>Reset Password</a></p>
                                        <p>This link will expire in 1 hour.</p>
                                        <p>If you did not request this, please ignore this email.</p>
                                    </body>
                                    </html>
                                ";
                                
                                $headers = [
                                    'MIME-Version: 1.0',
                                    'Content-type: text/html; charset=UTF-8',
                                    'From: noreply@yourdomain.com',
                                    'X-Mailer: PHP/' . phpversion()
                                ];
                                
                                if (mail($to, $subject, $messageBody, implode("\r\n", $headers))) {
                                    $message = "Password reset instructions have been sent to your email.";
                                } else {
                                    $error = "Failed to send email. Please try again later.";
                                }
                                */
                            } else {
                                $error = "System error: " . $insertStmt->error;
                            }
                            $insertStmt->close();
                        }
                    }
                } else {
                    // For security reasons, use the same message as success
                    $message = "If your registration number is valid, you will receive password reset instructions.";
                }
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .back-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="registrationNo">Registration Number</label>
                <input type="text" id="registrationNo" name="registrationNo" pattern="[0-9]{2}[A-Za-z]{3}[0-9]{4}" 
                       title="Format: 19ABC1234" required placeholder="Example: 19ABC1234">
                <small>Format: 2 digits, 3 letters, 4 digits (e.g., 19ABC1234)</small>
            </div>
            
            <button type="submit">Request Password Reset</button>
        </form>
        
        <div class="back-link">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
</body>
</html>