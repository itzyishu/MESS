document.addEventListener('DOMContentLoaded', () => {
    // Check if user is logged in
    const token = localStorage.getItem('token');
    if (!token) {
        // Redirect to login if no token
        window.location.href = 'login.html';
    }

    // Logout functionality
    const logoutLink = document.getElementById('logout-link');
    logoutLink.addEventListener('click', (e) => {
        e.preventDefault();
        // Remove token and redirect to login
        localStorage.removeItem('token');
        window.location.href = 'login.html';
    });

    // Profile link (placeholder)
    const profileLink = document.getElementById('profile-link');
    profileLink.addEventListener('click', (e) => {
        e.preventDefault();
        alert('Profile page coming soon!');
    });

    // Settings link (placeholder)
    const settingsLink = document.getElementById('settings-link');
    settingsLink.addEventListener('click', (e) => {
        e.preventDefault();
        alert('Settings page coming soon!');
    });
});