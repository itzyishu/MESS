document.addEventListener('DOMContentLoaded', function() {
    // Set today's date in the date field
    let dateField = document.querySelector('input[name="form_date"]');
    let now = new Date();
    now.setMinutes(now.getMinutes() + 330);
    let todayIST = now.toISOString().split('T')[0];
    dateField.value = todayIST;
    
    function validateRegistrationNo(regNo) {
        // Check length
        if (regNo.length !== 9) {
            return 'Registration number must be 9 characters long';
        }

        // Check first 2 characters (numbers)
        const firstTwoChars = regNo.slice(0, 2);
        if (!/^\d{2}$/.test(firstTwoChars)) {
            return 'Invalid Registration Number';
        }

        // Check next 3 characters (letters)
        const nextThreeChars = regNo.slice(2, 5);
        if (!/^[A-Za-z]{3}$/.test(nextThreeChars)) {
            return 'Invalid Registration Number';
        }

        // Check last 4 characters (numbers)
        const lastFourChars = regNo.slice(5);
        if (!/^\d{4}$/.test(lastFourChars)) {
            return 'Invalid Registration Number';
        }

        return null; // No error
    }
    const registrationError = validateRegistrationNo(signupRegInput.value);
            if (registrationError) {
                event.preventDefault();
                signupErrorMsg.textContent = registrationError;
                return;
            }
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
});