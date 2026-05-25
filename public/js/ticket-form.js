/**
 * ══════════════════════════════════════════════
 * BU CARE SYSTEM — ticket-form.js
 * Client-Side Interactive Form Validation Engine
 * ══════════════════════════════════════════════
 */

document.addEventListener('DOMContentLoaded', function() {
    const ticketForm  = document.getElementById('ticketSubmissionForm');
    const categorySel = document.getElementById('p_category');
    const descTextArea = document.getElementById('p_desc');
    const charCounter  = document.getElementById('charCounter');

    // Real-Time Description Character Counter
    if (descTextArea && charCounter) {
        descTextArea.addEventListener('input', function() {
            const currentLength = descTextArea.value.length;
            charCounter.textContent = `${currentLength}/1000 characters`;
            
            if (currentLength >= 10 && currentLength <= 1000) {
                charCounter.className = "form-hint text-success";
            } else {
                charCounter.className = "form-hint text-danger";
            }
        });
    }

    // Comprehensive Interception Validation Check
    if (ticketForm) {
        ticketForm.addEventListener('submit', function(event) {
            let formIsValid = true;
            let errorMessage = "";

            // Validate Category Field selection selection
            if (categorySel.value === "") {
                formIsValid = false;
                categorySel.classList.add('is-invalid');
                errorMessage += "• Please select an incident classification category.\n";
            } else {
                categorySel.classList.remove('is-invalid');
            }

            // Validate Description Boundary Metrics Metrics
            const descLength = descTextArea.value.trim().length;
            if (descLength < 10) {
                formIsValid = false;
                descTextArea.classList.add('is-invalid');
                errorMessage += "• Description details must contain at least 10 descriptive characters.\n";
            } else if (descLength > 1000) {
                formIsValid = false;
                descTextArea.classList.add('is-invalid');
                errorMessage += "• Description details cannot exceed the maximum allowance limit of 1000 characters.\n";
            } else {
                descTextArea.classList.remove('is-invalid');
            }

            // Halt operational traffic if violations exist
            if (!formIsValid) {
                event.preventDefault();
                alert(`Form Validation Error:\n\n${errorMessage}`);
            }
        });
    }
});