document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('login-form');
    const registrationInput = document.getElementById('registrationNo');
    const passwordInput = document.getElementById('password');
    const errorMessage = document.getElementById('error-message');

    // Validation function for registration number
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

    // Validation function for password
    function validatePassword(password) {
        // At least 8 characters
        if (password.length < 8) {
            return 'Password must be at least 8 characters long';
        }

        // At least one uppercase letter
        if (!/[A-Z]/.test(password)) {
            return 'Password must contain at least one uppercase letter';
        }

        // At least one lowercase letter
        if (!/[a-z]/.test(password)) {
            return 'Password must contain at least one lowercase letter';
        }

        // At least one number
        if (!/[0-9]/.test(password)) {
            return 'Password must contain at least one number';
        }

        // At least one special character
        if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
            return 'Password must contain at least one special character';
        }

        return null; // No error
    }

    // Form submission event listener
    form.addEventListener('submit', function(event) {
        // Reset error message
        errorMessage.textContent = '';

        // Validate registration number
        const registrationError = validateRegistrationNo(registrationInput.value);
        if (registrationError) {
            event.preventDefault();
            errorMessage.textContent = registrationError;
            return;
        }

        // Validate password
        const passwordError = validatePassword(passwordInput.value);
        if (passwordError) {
            event.preventDefault();
            errorMessage.textContent = passwordError;
            return;
        }
    });
});