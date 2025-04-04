<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error - Mess Feedback</title>
    <link rel="stylesheet" href="feedback.css">
    <style>
        .error-container {
            text-align: center;
            max-width: 600px;
            margin: 100px auto;
            padding: 30px;
            background-color: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .error-icon {
            color: #f44336;
            font-size: 60px;
            margin-bottom: 20px;
        }
        .error-title {
            color: #f44336;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .error-message {
            font-size: 16px;
            margin-bottom: 30px;
            color: #555;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .back-button:hover {
            background-color: #d32f2f;
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

    <div class="error-container">
        <div class="error-icon">✗</div>
        <h2 class="error-title">Submission Error</h2>
        <p class="error-message">
            There was an error processing your submission. Please try again.
            <?php if(isset($_GET['message'])): ?>
                <br><small style="color: #777;"><?php echo htmlspecialchars($_GET['message']); ?></small>
            <?php endif; ?>
        </p>
        <a href="javascript:history.back()" class="back-button">Go Back</a>
    </div>
</body>
</html>