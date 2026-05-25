/**
 * Bicol University CARE System — customer-ticket-filters.js
 * Live search + status tab filtering for the customer ticket dashboard table.
 * No page reload. Works entirely on data-status and data-search attributes
 * stamped onto each <tr> by Blade at render time.
 */
document.addEventListener('DOMContentLoaded', function () {

    const rows        = Array.from(document.querySelectorAll('#tickets-table tbody tr[data-status]'));
    const searchInput = document.getElementById('ticket-search');
    const tabs        = document.querySelectorAll('#status-tabs .status-tab');
    const countEl     = document.getElementById('ticket-count');
    const noResults   = document.getElementById('no-results-row');

    // Guard: if the filter bar isn't on this page, do nothing
    if (!searchInput || !countEl) return;

    let activeStatus = 'all';
    let searchTerm   = '';

    function updateCount(visible) {
        const total = rows.length;
        if (activeStatus === 'all' && searchTerm === '') {
            countEl.textContent = `Showing all ${total} ticket${total !== 1 ? 's' : ''}`;
        } else {
            countEl.textContent = `${visible} of ${total} ticket${total !== 1 ? 's' : ''} shown`;
        }
    }

    function applyFilters() {
        let visible = 0;

        rows.forEach(function (row) {
            const matchStatus = activeStatus === 'all' || row.dataset.status === activeStatus;
            const matchSearch = searchTerm === '' || row.dataset.search.includes(searchTerm);

            if (matchStatus && matchSearch) {
                row.classList.remove('hidden-row');
                visible++;
            } else {
                row.classList.add('hidden-row');
            }
        });

        if (noResults) {
            noResults.style.display = visible === 0 ? '' : 'none';
        }

        updateCount(visible);
    }

    // Status tab clicks
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('active'); });
            tab.classList.add('active');
            activeStatus = tab.dataset.status;
            applyFilters();
        });
    });

    // Search input — debounced 200ms
    var debounce;
    searchInput.addEventListener('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(function () {
            searchTerm = searchInput.value.trim().toLowerCase();
            applyFilters();
        }, 200);
    });

    // Initialize count on page load
    updateCount(rows.length);

});