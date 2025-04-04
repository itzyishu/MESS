<?php
// Include database connection
include 'db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data and sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $registration_number = mysqli_real_escape_string($conn, $_POST['registration_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $block = mysqli_real_escape_string($conn, $_POST['block']);
    $room = mysqli_real_escape_string($conn, $_POST['room']);
    $mess_type = mysqli_real_escape_string($conn, $_POST['mess_type']);
    $form_date = mysqli_real_escape_string($conn, $_POST['form_date']);
    $breakfast_suggestions = mysqli_real_escape_string($conn, $_POST['breakfast_suggestions']);
    $lunch_suggestions = mysqli_real_escape_string($conn, $_POST['lunch_suggestions']);
    $snack_suggestions = mysqli_real_escape_string($conn, $_POST['snack_suggestions']);
    $dinner_suggestions = mysqli_real_escape_string($conn, $_POST['dinner_suggestions']);
    
    // Handle suggestion days (checkboxes)
    $suggestion_days = isset($_POST['suggestion_days']) ? implode(", ", $_POST['suggestion_days']) : "";
    $suggestion_days = mysqli_real_escape_string($conn, $suggestion_days);
    
    $mass_feasibility = mysqli_real_escape_string($conn, $_POST['mass_feasibility']);
    $repeat_frequency = mysqli_real_escape_string($conn, $_POST['repeat_frequency']);
    $additional_remarks = mysqli_real_escape_string($conn, $_POST['additional_remarks']);
    
    // Insert data into database
    $sql = "INSERT INTO mess_feedback (
                name, registration_number, email, phone, gender, block, room, 
                mess_type, form_date, breakfast_suggestions, lunch_suggestions, 
                snack_suggestions, dinner_suggestions, suggestion_days,
                mass_feasibility, repeat_frequency, additional_remarks, 
                submission_date
            ) VALUES (
                '$name', '$registration_number', '$email', '$phone', '$gender', 
                '$block', '$room', '$mess_type', '$form_date', '$breakfast_suggestions', 
                '$lunch_suggestions', '$snack_suggestions', '$dinner_suggestions', 
                '$suggestion_days', '$mass_feasibility', '$repeat_frequency', 
                '$additional_remarks', NOW()
            )";
    
    $result = mysqli_query($conn, $sql);
    
    // Redirect with success or error message
    if ($result) {
        header("Location: success.php");
        exit;
    } else {
        header("Location: error.php?message=" . urlencode(mysqli_error($conn)));
        exit;
    }
    
    // Close connection
    //mysqli_close($conn);
}
?>