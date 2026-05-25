// Delete confirmation modal
let deleteId = null;
let deleteModal = null;

document.addEventListener('DOMContentLoaded', function () {

    // Initialize delete modal if it exists
    const modalEl = document.getElementById('deleteModal');
    if (modalEl) {
        deleteModal = new bootstrap.Modal(modalEl);
    }

    // Confirm delete button
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (deleteId) {
                document.getElementById('delete-' + deleteId).submit();
            }
        });
    }

    // Search input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', filterTable);
    }

    // Status filter
    const filterStatus = document.getElementById('filterStatus');
    if (filterStatus) {
        filterStatus.addEventListener('change', filterTable);
    }

    // Priority filter
    const filterPriority = document.getElementById('filterPriority');
    if (filterPriority) {
        filterPriority.addEventListener('change', filterTable);
    }

});

// Show delete modal
function confirmDelete(id) {
    deleteId = id;
    deleteModal.show();
}

// Live search and filter
function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('filterStatus').value.toLowerCase();
    const priority = document.getElementById('filterPriority').value.toLowerCase();
    const rows = document.querySelectorAll('#ticketTable tbody tr');
    let visible = 0;

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const rowStatus = row.cells[4].innerText.toLowerCase();
        const rowPriority = row.cells[3].innerText.toLowerCase();

        const matchSearch = text.includes(search);
        const matchStatus = status === '' || rowStatus.includes(status);
        const matchPriority = priority === '' || rowPriority.includes(priority);

        if (matchSearch && matchStatus && matchPriority) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });

    const countEl = document.getElementById('ticketCount');
    if (countEl) {
        countEl.innerText = visible + ' tickets';
    }
}

// Reset filters
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterPriority').value = '';
    filterTable();
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    let valid = true;
    const inputs = form.querySelectorAll('[required]');

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    });

    return valid;
}