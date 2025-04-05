<?php
//session_start();
//require_once 'connect.php';
// Email details
$to = "amitaraut084@gmail.com";
$subject = "Test Email Subject";
$message = "This is a test email message.";
$headers = "From: mansi24kri@gmail.com";


if(mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Contact to the admin at mansi.kumari2023@vitstudent.ac.in";
}
?>