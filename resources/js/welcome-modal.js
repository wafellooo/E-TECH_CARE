/**
 * BICOL UNIVERSITY CARE — welcome-modal.js
 * JavaScript logic for login modal on welcome page (welcome.blade.php)
 */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-show login modal if there are form validation errors
    const errorElements = document.querySelectorAll('.form-alert-banner');
    if (errorElements.length > 0) {
        const loginModal = document.getElementById('loginModal');
        if (loginModal) {
            const myModal = new bootstrap.Modal(loginModal);
            myModal.show();
        }
    }
});
