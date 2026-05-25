/**
 * Bicol University CARE System — customer-ticket-filters.js
 * Live search + status tab filtering for the customer ticket dashboard table.
 * No page reload. Works entirely on data-status and data-search attributes
 * stamped onto each <tr> by Blade at render time.
 * 
 * FILTER LOGIC:
 * - "All" tab: Shows all ACTIVE tickets (excludes closed)
 * - "Closed" tab: Shows only closed tickets (acts as history archive)
 * - Other tabs: Shows only that specific status
 */
document.addEventListener('DOMContentLoaded', function () {

    const rows        = Array.from(document.querySelectorAll('#tickets-table tbody tr[data-status]'));
    const searchInput = document.getElementById('ticket-search');
    const tabs        = document.querySelectorAll('#status-tabs .status-tab');
    const countEl     = document.getElementById('ticket-count');
    const noResults   = document.getElementById('no-results-row');

    // Guard: if the filter bar isn't on this page, do nothing
    if (!searchInput || !countEl) return;

    let activeStatus = 'all'; // Default: "All" tab is active
    let searchTerm   = '';

    function updateCount(visible) {
        const total = rows.length;
        const closedCount = rows.filter(r => r.dataset.status === 'closed').length;
        const activeCount = total - closedCount;
        
        if (activeStatus === 'all') {
            countEl.textContent = `Showing ${visible} active ticket${visible !== 1 ? 's' : ''} (${closedCount} in history)`;
        } else if (activeStatus === 'closed') {
            countEl.textContent = `Showing ${visible} closed ticket${visible !== 1 ? 's' : ''} (history archive)`;
        } else {
            countEl.textContent = `${visible} of ${total} ticket${total !== 1 ? 's' : ''} shown`;
        }
    }

    function applyFilters() {
        let visible = 0;

        rows.forEach(function (row) {
            let matchStatus;
            
            // Core logic: "All" always excludes closed. "Closed" only shows closed.
            if (activeStatus === 'all') {
                // "All" tab: show everything EXCEPT closed tickets
                matchStatus = row.dataset.status !== 'closed';
            } else if (activeStatus === 'closed') {
                // "Closed" tab: show ONLY closed tickets (history archive)
                matchStatus = row.dataset.status === 'closed';
            } else {
                // Any other tab: show only that specific status
                matchStatus = row.dataset.status === activeStatus;
            }

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

    // Initialize on page load: apply default filters (exclude closed)
    applyFilters();

});
