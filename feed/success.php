<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Success - Mess Feedback</title>
    <link rel="stylesheet" href="feedback.css">
    <style>
        .success-container {
            text-align: center;
            max-width: 600px;
            margin: 100px auto;
            padding: 30px;
            background-color: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success-icon {
            color: #4CAF50;
            font-size: 60px;
            margin-bottom: 20px;
        }
        .success-title {
            color: #4CAF50;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .success-message {
            font-size: 16px;
            margin-bottom: 30px;
            color: #555;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <nav class="nav-bar">
        <div class="nav-logo">MyMess</div>
        <div class="nav-links">
            <a href="login/dashboard.php">Home</a>
            <a href="https://messit.vinnovateit.com/">Mess Menu</a>
            <a href="login/login.php">Registration</a>
            <a href="next/feedback.html">Feedback</a>
            <a href="#">Contact</a>
            <a href="#">Profile</a>
        </div>
    </nav>

    <div class="success-container">
        <div class="success-icon">✓</div>
        <h2 class="success-title">Submission Successful!</h2>
        <p class="success-message">Thank you for your feedback. Your suggestions have been submitted successfully.</p>
        <a href="feedback.php" class="back-button">Back to Form</a>
    </div>
</body>
</html>