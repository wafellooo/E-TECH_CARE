/**
 * BICOL UNIVERSITY CARE — customer-login.js
 * JavaScript logic for login form (login.blade.php)
 */

/**
 * Toggle password field visibility
 */
function togglePassword() {
    const passwordInput = document.getElementById('passwordInput');
    const eyeIcon = document.getElementById('eyeIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}

/**
 * Show toast notification
 */
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toastHTML = `
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" style="
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        ">
            <div class="toast-header" style="background: ${type === 'success' ? '#10b981' : '#ef4444'}; color: white; border: none;">
                <i class="fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'} me-2"></i>
                <strong class="me-auto">${type === 'success' ? 'Success' : 'Error'}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" style="background: ${type === 'success' ? '#d1fae5' : '#fee2e2'}; color: ${type === 'success' ? '#065f46' : '#7f1d1d'};">
                ${message}
            </div>
        </div>
    `;
    
    toastContainer.innerHTML = toastHTML;
    
    // Auto-hide after 4 seconds
    setTimeout(() => {
        const toast = toastContainer.querySelector('.toast');
        if (toast) toast.classList.remove('show');
    }, 4000);
}

/**
 * Create toast container if it doesn't exist
 */
function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
    return container;
}

/**
 * Show success toast when login succeeds (check for session flash message)
 */
document.addEventListener('DOMContentLoaded', function() {
    // Check if success message exists
    const successDiv = document.querySelector('div[style*="rgba(16,185,129"]');
    if (successDiv && successDiv.textContent.includes('circle-check')) {
        const message = successDiv.textContent.replace(/[^\w\s]/g, '').trim();
        showToast(message || 'Login successful! Redirecting...', 'success');
        
        // Redirect to dashboard after 2 seconds
        setTimeout(() => {
            window.location.href = '/customer/dashboard';
        }, 2000);
    }
});
