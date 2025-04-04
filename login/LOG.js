document.addEventListener('DOMContentLoaded', function() {
    // UI Toggle Elements
    const login = document.getElementById('login');
    const sign = document.getElementById('sign');
    const loginin = document.getElementById('loginin');
    const signin = document.getElementById('signin');
    
    // Default display state
    login.style.display = "block";
    sign.style.display = "none";
    
    // Form elements - need to select by form and input ID to handle duplicate IDs
    const loginForm = login.querySelector('#login-form');
    const signupForm = sign.querySelector('#login-form');
    
    const loginRegInput = login.querySelector('#registrationNo');
    const loginPwdInput = login.querySelector('#password');
    const loginErrorMsg = login.querySelector('#error-message');
    
    const signupRegInput = sign.querySelector('#registrationNo');
    const signupEmailInput = sign.querySelector('#email');
    const signupPwdInput = sign.querySelector('#password');
    const signupErrorMsg = sign.querySelector('#error-message');
    
    // Toggle between login and signup sections
    signin.addEventListener('click', function(event) {
        event.preventDefault();
        
        // Clear fields before showing signup form
        signupRegInput.value = '';
        signupEmailInput.value = '';
        signupPwdInput.value = '';
        signupErrorMsg.textContent = '';
        
        // Show signup form, hide login form
        sign.style.display = "block";
        login.style.display = "none";
    });
    
    loginin.addEventListener('click', function(event) {
        event.preventDefault();
        
        // Clear fields before showing login form
        loginRegInput.value = '';
        loginPwdInput.value = '';
        loginErrorMsg.textContent = '';
        
        // Show login form, hide signup form
        login.style.display = "block";
        sign.style.display = "none";
    });

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

    // Email validation function
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            return 'Please enter a valid email address';
        }
        return null;
    }

    // Login form validation
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            // Reset error message
            loginErrorMsg.textContent = '';

            // Validate registration number
            const registrationError = validateRegistrationNo(loginRegInput.value);
            if (registrationError) {
                event.preventDefault();
                loginErrorMsg.textContent = registrationError;
                return;
            }

            // Validate password
            const passwordError = validatePassword(loginPwdInput.value);
            if (passwordError) {
                event.preventDefault();
                loginErrorMsg.textContent = passwordError;
                return;
            }
        });
    }

    // Signup form validation
    if (signupForm) {
        signupForm.addEventListener('submit', function(event) {
            // Reset error message
            signupErrorMsg.textContent = '';

            // Validate registration number
            const registrationError = validateRegistrationNo(signupRegInput.value);
            if (registrationError) {
                event.preventDefault();
                signupErrorMsg.textContent = registrationError;
                return;
            }

            // Validate email
            const emailError = validateEmail(signupEmailInput.value);
            if (emailError) {
                event.preventDefault();
                signupErrorMsg.textContent = emailError;
                return;
            }

            // Validate password
            const passwordError = validatePassword(signupPwdInput.value);
            if (passwordError) {
                event.preventDefault();
                signupErrorMsg.textContent = passwordError;
                return;
            }
        });
    }
});