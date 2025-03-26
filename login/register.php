<?php
session_start();
include 'connect.php';

if(isset($_POST['next-button'])) {
    $registrationNo = $_POST['registrationNo'];
    $password = $_POST['password'];
    $hashed_password = md5($password);

    // Validate input (adding an extra layer of security)
    if (empty($registrationNo) || empty($password)) {
        die("Registration Number and Password cannot be empty");
    }

    // Prevent SQL Injection (ideally, use prepared statements)
    $registrationNo = $conn->real_escape_string($registrationNo);

    // Check if registration number already exists
    $checkreg = "SELECT * FROM register WHERE registrationNo='$registrationNo'";
    $result = $conn->query($checkreg);
    
    if($result->num_rows > 0) {
        // Registration number exists - attempt login
        $sql = "SELECT * FROM register WHERE registrationNo='$registrationNo' AND password='$hashed_password'";
        $login_result = $conn->query($sql);
        
        if($login_result->num_rows > 0) {
            // Successful login
            $row = $login_result->fetch_assoc();
            $_SESSION['registrationNo'] = $row['registrationNo'];
            
            // Set a session variable to show login success message
            $_SESSION['login_success'] = true;
            
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect password
            $_SESSION['error_message'] = "Incorrect Password";
            header("Location: login.php");
            exit();
        }
    } else {
        // New registration
        $insertQuery = "INSERT INTO register(registrationNo, password) VALUES ('$registrationNo', '$hashed_password')";
        
        if($conn->query($insertQuery) === TRUE) {
            // Set a session variable to show registration success message
            $_SESSION['registration_success'] = true;
            
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>