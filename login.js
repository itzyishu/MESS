document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(loginForm);

        try {
            const response = await fetch('http://localhost/mess/login.php', {
                method: 'post',
                body: formData
            });

            if (response.ok) {
                // Assuming the PHP script redirects on success
                window.location.href = '/dashboard.php';
            } else {
                errorMessage.textContent = 'Login failed. Please check your registration number and password.';
            }
        } catch (error) {
            errorMessage.textContent = 'Network error. Please try again.';
            console.error('Login error:', error);
        }
    });
});