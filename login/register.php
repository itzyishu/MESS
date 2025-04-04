<?php
session_start();
include 'connect.php';

if(isset($_POST['next-button'])) {
    $registrationNo = $_POST['registrationNo'];
    $password = $_POST['password'];
    $hashed_password = md5($password);
    
    // Check if we have an email field (signup form)
    $isSignup = isset($_POST['email']);

    // Validate input (adding an extra layer of security)
    if (empty($registrationNo) || empty($password)) {
        $_SESSION['error_message'] = "Registration Number and Password cannot be empty";
        header("Location: login.php");
        exit();
    }

    // Server-side validation for registration number format
    if (strlen($registrationNo) !== 9 || 
        !ctype_digit(substr($registrationNo, 0, 2)) || 
        !ctype_alpha(substr($registrationNo, 2, 3)) || 
        !ctype_digit(substr($registrationNo, 5, 4))) {
        $_SESSION['error_message'] = "Invalid Registration Number format";
        header("Location: login.php");
        exit();
    }

    // Server-side validation for password strength
    if (strlen($password) < 8) {
        $_SESSION['error_message'] = "Password must be at least 8 characters long";
        header("Location: login.php");
        exit();
    }

    // Prevent SQL Injection (ideally, use prepared statements)
    $registrationNo = $conn->real_escape_string($registrationNo);

    // Check if registration number already exists
    $checkreg = "SELECT * FROM register WHERE registrationNo='$registrationNo'";
    $result = $conn->query($checkreg);
    
    if($result->num_rows > 0) {
        // Registration number exists
        if ($isSignup) {
            // User tried to signup with existing registration number
            $_SESSION['error_message'] = "Registration number already exists. Please login instead.";
            header("Location: login.php");
            exit();
        } else {
            // Attempt login
            $sql = "SELECT * FROM register WHERE registrationNo='$registrationNo' AND password='$hashed_password'";
            $login_result = $conn->query($sql);
            
            if($login_result->num_rows > 0) {
                // Successful login
                $row = $login_result->fetch_assoc();
                
                // Store user information in session
                $_SESSION['registrationNo'] = $row['registrationNo'];
                $_SESSION['user_id'] = $row['id']; // Assuming your table has an id column
                $_SESSION['logged_in'] = true;

                // Set success message
             //   $_SESSION['login_success_message'] = "Login Successful";

                // Redirect directly to dashboard with success message
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                $_SESSION['error_message'] = "Incorrect Password";
                header("Location: login.php");
                exit();
            }
        }
    } else {
        // Registration number doesn't exist
        if ($isSignup) {
            // Process signup with email
            $email = $_POST['email'];
            
            // Validate email
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_message'] = "Invalid email format";
                header("Location: login.php");
                exit();
            }
            
            // New registration with email
            $insertQuery = "INSERT INTO register(registrationNo, email, password) VALUES ('$registrationNo', '$email', '$hashed_password')";
        } else {
            // New registration without email (basic form)
            $insertQuery = "INSERT INTO register(registrationNo, password) VALUES ('$registrationNo', '$hashed_password')";
        }
        
        if($conn->query($insertQuery) === TRUE) {
            // Set a session variable to show registration success message
            $_SESSION['registration_success'] = true;
            
            // Redirect to login.php after successful registration
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Registration failed: " . $conn->error;
            header("Location: login.php");
            exit();
        }
    }
}
?>
