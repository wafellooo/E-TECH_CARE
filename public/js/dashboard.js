/**
 * ══════════════════════════════════════════════
 * BU CARE SYSTEM — dashboard.js
 * Client Side Dynamic Operational Logic Engine
 * ══════════════════════════════════════════════
 */

document.addEventListener('DOMContentLoaded', function() {
    // Collect DOM handles
    const searchInput = document.getElementById('tableSearch');
    const statusFilter = document.getElementById('statusFilter');
    const rows         = document.querySelectorAll('.ticket-row');
    const deleteModal  = document.getElementById('confirmDeleteModal');

    /**
     * Executes real-time filtering on the ticket log data grid.
     */
    function performFilterAction() {
        const searchString  = searchInput.value.toLowerCase().trim();
        const selectedStatus = statusFilter.value;

        rows.forEach(row => {
            const textValue = row.textContent.toLowerCase();
            const rowStatus = row.getAttribute('data-status');

            // Logical truth mapping
            const matchesSearch = textValue.includes(searchString);
            const matchesStatus = (selectedStatus === 'all' || rowStatus === selectedStatus);

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Attach tracking event listeners
    if (searchInput)  searchInput.addEventListener('input', performFilterAction);
    if (statusFilter) statusFilter.addEventListener('change', performFilterAction);

    /**
     * Captures deletion clicks and safely targets the active row identifier.
     */
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button   = event.relatedTarget;
            const ticketId = button.getAttribute('data-id');
            
            // Set contextual string indicators inside the modal interface
            document.getElementById('modalTargetId').textContent = ticketId;
            
            // Dynamically alter the form action string parameter to point to the correct table item
            document.getElementById('deleteFormAction').setAttribute('action', `/customer/tickets/${ticketId}`);
        });
    }
});