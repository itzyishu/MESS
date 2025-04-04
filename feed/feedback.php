<?php
session_start();
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Mess Registration Form</title>
    <link rel="stylesheet" href="feedback.css">
</head>
<body>

    <nav class="nav-bar">
        <div class="nav-logo">MyMess</div>
        <div class="nav-links">
            <a href="../login/dashboard.php">Home</a>
            <a href="https://messit.vinnovateit.com/">Mess Menu</a>
            <a href="../login/login.php">Registration</a>
            <a href="#">Profile</a>
        </div>
    </nav>

    <form class="feedback-container" id="feedbackForm" method="POST" action="process_feedback.php">
        <h2 class="feedback-title">Mess Feedback</h2>

        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="feedback-input" required>
        </div>

        <div class="form-group">
            <label>Registration Number:</label>
            <input type="text" name="registration_number" class="feedback-input" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="feedback-input" required>
        </div>

        <div class="form-group">
            <label>Phone Number:</label>
            <input type="tel" name="phone" class="feedback-input" required>
        </div>

        <div class="form-group">
            <label>Gender:</label>
            <select name="gender" class="feedback-input" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Block:</label>
            <input type="text" name="block" class="feedback-input" required>
        </div>

        <div class="form-group">
            <label>Room:</label>
            <input type="text" name="room" class="feedback-input" required>
        </div>

        <div class="form-group">
            <label>Mess Type:</label>
            <div class="satisfaction-options">
                <label class="satisfaction-option">
                    <input type="radio" name="mess_type" value="veg"> Vegetarian
                </label>
                <label class="satisfaction-option">
                    <input type="radio" name="mess_type" value="non-veg"> Non-Vegetarian
                </label>
                <label class="satisfaction-option">
                    <input type="radio" name="mess_type" value="special"> Special
                </label>
            </div>
        </div>
        <label>Date:</label>
        <input type="date" name="form_date" class="feedback-input" readonly>

        <div class="meal-suggestion-section">
            <h3>Meal Suggestions</h3>
            <label>Breakfast Suggestions:</label>
            <textarea name="breakfast_suggestions" rows="4" class="feedback-input" placeholder="Suggest breakfast dishes, preferred items, frequency, etc."></textarea>

            <label>Lunch Suggestions:</label>
            <textarea name="lunch_suggestions" rows="4" class="feedback-input" placeholder="Suggest lunch dishes, preferred items, frequency, etc."></textarea>

            <label>Snack Suggestions:</label>
            <textarea name="snack_suggestions" rows="4" class="feedback-input" placeholder="Suggest snack items, preferred items, frequency, etc."></textarea>

            <label>Dinner Suggestions:</label>
            <textarea name="dinner_suggestions" rows="4" class="feedback-input" placeholder="Suggest dinner dishes, preferred items, frequency, etc."></textarea>
        </div>

        <div class="week-suggestion-section" id="daySuggestionSection">
            <h3>On Which Day Would You Like to Eat Your Suggestions?</h3>
            <label class="satisfaction1-option"><input type="checkbox" name="suggestion_days[]" value="monday"> Monday</label><br>
            <label class="satisfaction1-option"><input type="checkbox" name="suggestion_days[]" value="tuesday"> Tuesday</label><br>
            <label class="satisfaction1-option"><input type="checkbox" name="suggestion_days[]" value="wednesday"> Wednesday</label><br>
            <label class="satisfaction1-option"><input type="checkbox" name="suggestion_days[]" value="thursday"> Thursday</label><br>
            <label class="satisfaction1-option"><input type="checkbox" name="suggestion_days[]" value="friday"> Friday</label><br>
            <label class="satisfaction1-option"><input type="checkbox" name="suggestion_days[]" value="saturday"> Saturday</label><br>
            <label class="satisfaction1-option"><input type="checkbox" name="suggestion_days[]" value="sunday"> Sunday</label><br>
        </div>

        <div class="additional-options" id="additionalOptions">
            <div class="form-group">
                <br><label>Is the suggestion feasible for mass production?</label>
                <select name="mass_feasibility" class="feedback-input" >
                    <option value="">Select Feasibility</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="form-group">
                <label>How often would you like to repeat the suggestion?</label>
                <select name="repeat_frequency" class="feedback-input" >
                    <option value="">Select Frequency</option>
                    <option value="weekly">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="bi-weekly">Every 2 Weeks</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
        </div>
        <div class="additional-remark-section">
            <label>Additional Remarks:</label>
            <textarea name="additional_remarks" rows="4" class="feedback-input" placeholder="Any other comments or suggestions?"></textarea>
        </div>
        <button type="submit" class="send-button">SEND</button>
        <div id="loading" class="loading-animation" style="display:none;"></div>
        <div id="success-message" class="success-message">Submission Successful!</div>
    </form>

    <script > 
        document.addEventListener('DOMContentLoaded', function() {
    // Set today's date in the date field
    let dateField = document.querySelector('input[name="form_date"]');
    let now = new Date();
    now.setMinutes(now.getMinutes() + 330);
    let todayIST = now.toISOString().split('T')[0];
    dateField.value = todayIST;
    
    // Get form and input elements
    const form = document.getElementById('feedbackForm');
    const regInput = document.querySelector('input[name="registration_number"]');
    const phoneInput = document.querySelector('input[name="phone"]');
    
    // Add help text elements
    const regHelpText = document.createElement('div');
    regHelpText.className = 'help-text';
    regHelpText.style.color = '#666';
    regHelpText.style.fontSize = '12px';
    regHelpText.style.marginTop = '5px';
   // regHelpText.textContent = 'Format: 2 digits + 3 letters + 4 digits (e.g., 21ABC1234)';
    regInput.parentNode.appendChild(regHelpText);
    
    const phoneHelpText = document.createElement('div');
    phoneHelpText.className = 'help-text';
    phoneHelpText.style.color = '#666';
    phoneHelpText.style.fontSize = '12px';
    phoneHelpText.style.marginTop = '5px';
   // phoneHelpText.textContent = 'Must be exactly 10 digits';
    phoneInput.parentNode.appendChild(phoneHelpText);
    
    // Form submission handler
    form.addEventListener('submit', function(event) {
        // Validate registration number
        const regValue = regInput.value.trim();
        const regPattern = /^\d{2}[A-Za-z]{3}\d{4}$/;
        
        if (!regPattern.test(regValue)) {
            event.preventDefault();
            regInput.style.borderColor = 'red';
            regHelpText.style.color = 'red';
            regHelpText.textContent = 'Invalid format! Must be 2 digits + 3 letters + 4 digits';
            regInput.focus();
            return;
        } else {
            regInput.style.borderColor = '';
            regHelpText.style.color = '#666';
            regHelpText.textContent = 'Format: 2 digits + 3 letters + 4 digits (e.g., 21ABC1234)';
        }
        
        // Validate phone number
        const phoneValue = phoneInput.value.trim();
        const phonePattern = /^\d{10}$/;
        
        if (!phonePattern.test(phoneValue)) {
            event.preventDefault();
            phoneInput.style.borderColor = 'red';
            phoneHelpText.style.color = 'red';
            phoneHelpText.textContent = 'Invalid phone number! Must be exactly 10 digits';
            phoneInput.focus();
            return;
        } else {
            phoneInput.style.borderColor = '';
            phoneHelpText.style.color = '#666';
            phoneHelpText.textContent = 'Must be exactly 10 digits';
        }
        
        // Show loading indicator
        document.getElementById('loading').style.display = 'block';
    });
    
    // Show/hide suggestion sections based on textarea content
    document.querySelectorAll('textarea').forEach(function(textarea) {
        textarea.addEventListener('input', function() {
            let breakfast = document.querySelector('textarea[name="breakfast_suggestions"]').value;
            let lunch = document.querySelector('textarea[name="lunch_suggestions"]').value;
            let snack = document.querySelector('textarea[name="snack_suggestions"]').value;
            let dinner = document.querySelector('textarea[name="dinner_suggestions"]').value;

            if (breakfast || lunch || snack || dinner) {
                document.getElementById('daySuggestionSection').classList.add('visible');
                document.getElementById('additionalOptions').classList.add('visible');
            } else {
                document.getElementById('daySuggestionSection').classList.remove('visible');
                document.getElementById('additionalOptions').classList.remove('visible');
            }
        });
    });
    
   /* // Initialize the visibility
    document.getElementById('daySuggestionSection').style.display = 'none';
    document.getElementById('additionalOptions').style.display = 'none';*/
});
    </script>
    <script src="feedback.js"></script>
</body>
</html>
