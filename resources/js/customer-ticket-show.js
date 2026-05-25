/**
 * BICOL UNIVERSITY CARE — customer-ticket-show.js
 * JavaScript logic for ticket detail view (show.blade.php)
 */

// Mirror dashboard logout behaviour
document.addEventListener('DOMContentLoaded', function() {
    const signoutLink = document.getElementById('account-signout-link');
    const logoutForm  = document.getElementById('logout-form-processor');
    
    if (signoutLink && logoutForm) {
        signoutLink.addEventListener('click', e => {
            e.preventDefault();
            logoutForm.submit();
        });
    }
});
