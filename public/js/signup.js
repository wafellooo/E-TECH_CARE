/* ==========================================
   SIGN UP PAGE SCRIPTS
   ========================================== */

/**
 * Toggle student/faculty conditional fields
 * based on selected user type radio button
 */
function toggleFields(type) {
    const studentFields = document.getElementById('studentFields');
    const facultyFields = document.getElementById('facultyFields');

    const studentInputs = studentFields.querySelectorAll('input, select');
    const facultyInputs = facultyFields.querySelectorAll('input, select');

    if (type === 'student') {
        studentFields.classList.add('active');
        facultyFields.classList.remove('active');

        studentInputs.forEach(input => input.setAttribute('required', 'true'));
        facultyInputs.forEach(input => input.removeAttribute('required'));

    } else if (type === 'faculty') {
        facultyFields.classList.add('active');
        studentFields.classList.remove('active');

        facultyInputs.forEach(input => input.setAttribute('required', 'true'));
        studentInputs.forEach(input => input.removeAttribute('required'));
    }
}

/**
 * Initialize page — hide conditional fields on load
 * but restore them if validation failed (old input exists)
 */
document.addEventListener('DOMContentLoaded', function () {
    const studentRadio = document.getElementById('studentRadio');
    const facultyRadio = document.getElementById('facultyRadio');

    // Restore state after failed form submission
    if (studentRadio && studentRadio.checked) {
        toggleFields('student');
    } else if (facultyRadio && facultyRadio.checked) {
        toggleFields('faculty');
    }
});