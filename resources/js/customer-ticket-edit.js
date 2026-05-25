/**
 * BICOL UNIVERSITY CARE — customer-ticket-edit.js
 * JavaScript logic for ticket edit form (edit.blade.php)
 */

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
