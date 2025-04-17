 // Add this if you want the search and filter functionality
 document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[type="text"]');
    const roleSelect = document.querySelector('select');
    const rows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleFilter = roleSelect.value.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const role = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

            const matchesSearch = text.includes(searchTerm);
            const matchesRole = !roleFilter || role.includes(roleFilter);

            row.style.display = matchesSearch && matchesRole ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    roleSelect.addEventListener('change', filterTable);
});
