document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const teacherFilter = document.getElementById('teacherFilter');
    const yearLevelFilter = document.getElementById('yearLevelFilter');
    const schoolYearFilter = document.getElementById('schoolYearFilter');
    const tableRows = document.querySelectorAll('tbody tr');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const studentCheckboxes = document.querySelectorAll('.studentCheckbox');
    const exportCsvButton = document.getElementById('exportCsvButton');
    const deleteSelectedButton = document.getElementById('deleteSelectedButton');

    // Filter Table Function
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedTeacher = teacherFilter ? teacherFilter.value : '';
        const selectedYearLevel = yearLevelFilter.value;
        const selectedSchoolYear = schoolYearFilter.value;

        const urlParams = new URLSearchParams();

        if (searchTerm) {
            urlParams.set('search', searchTerm);
        }

        if (selectedYearLevel) {
            urlParams.set('year_level_id', selectedYearLevel);
        }

        if (selectedSchoolYear) {
            urlParams.set('school_year_id', selectedSchoolYear);
        }

        // Only apply teacher filter for admins
        if (teacherFilter && selectedTeacher && teacherFilter.disabled === false) {
            urlParams.set('user_id', selectedTeacher);
        }

        // Get current page if it exists
        const currentPage = new URLSearchParams(window.location.search).get('page');
        if (currentPage) {
            urlParams.set('page', currentPage);
        }

        // Reload the page with new filters
        window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
    }

    // Event listeners for search and filters
    let filterTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(filterTable, 1000);
    });

    // Add event listeners for filters
    [teacherFilter, yearLevelFilter, schoolYearFilter].forEach(filter => {
        if (filter) {
            filter.addEventListener('change', () => {
                // Reset to first page when changing filters
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.delete('page');
                window.history.replaceState({}, '', `${window.location.pathname}?${urlParams.toString()}`);

                filterTable();
            });
        }
    });


    // Update URL with current page when clicking pagination links
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const url = new URL(this.href);
            // Preserve existing filters
            if (searchInput?.value) {
                url.searchParams.set('search', searchInput.value);
            }
            if (teacherFilter?.value) {
                url.searchParams.set('user_id', teacherFilter.value);
            }
            if (yearLevelFilter?.value) {
                url.searchParams.set('year_level_id', yearLevelFilter.value);
            }
            if (schoolYearFilter?.value) {
                url.searchParams.set('school_year_id', schoolYearFilter.value);
            }
            window.location.href = url.toString();
        });
    });


    // Select All Checkbox Functionality
    selectAllCheckbox.addEventListener('change', function () {
        studentCheckboxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            if (row.style.display !== 'none') {
                checkbox.checked = selectAllCheckbox.checked;
            }
        });
    });

    // Export CSV Functionality
    exportCsvButton.addEventListener('click', function () {
        const selectedStudents = Array.from(studentCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => {
                const row = checkbox.closest('tr');
                const nameCell = row.cells[2].textContent
                    .trim(); // Get the full name from column 2
                const nameParts = nameCell.split(',').map(part => part
                    .trim()); // Split the name into parts

                return {
                    id: checkbox.value,
                    LRN_num: row.cells[1].textContent.trim(),
                    lastname: nameParts[0] || '', // Extract lastname
                    firstname: nameParts[1] || '', // Extract firstname
                    middlename: nameParts[2] || '', // Extract middlename
                    suffix: nameParts[3] || '', // Extract suffix
                    age: row.cells[3].textContent.trim(),
                    gender: row.cells[4].textContent.trim(),
                    birthdate: row.cells[5].textContent.trim(),
                    section: row.cells[6].textContent.trim(),
                    year_level_id: row.cells[7].getAttribute('data-year-level'),
                    school_year_id: row.cells[8].getAttribute('data-school-year')
                };
            });

        if (selectedStudents.length > 0) {
            const csvContent = [
                ['user_id', 'LRN_num', 'name', 'age', 'gender', 'birthdate', 'section',
                    'year_level_id', 'school_year_id'
                ].join(','),
                ...selectedStudents.map(student => [
                    `"{{ Auth::id(user_id) }}"`,
                    `"${student.LRN_num}"`,
                    `"${student.lastname}, ${student.firstname}, ${student.middlename}, ${student.suffix}"`,
                    `"${student.age}"`,
                    `"${student.gender}"`,
                    `"${student.birthdate}"`,
                    `"${student.section}"`,
                    `"${student.year_level_id}"`,
                    `"${student.school_year_id}"`
                ].join(','))
            ].join('\n');

            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);

            link.setAttribute('href', url);
            link.setAttribute('download', 'student_lists.csv');
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } else {
            alert('Please select at least one student to export.');
        }
    });

    // Delete Selected Functionality
    deleteSelectedButton.addEventListener('click', function () {
        const selectedStudents = Array.from(studentCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        if (selectedStudents.length > 0) {
            if (confirm('Are you sure you want to delete the selected students?')) {
                fetch('/teacher/students/delete-selected', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                    },
                    body: JSON.stringify({
                        students: selectedStudents
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        } else {
            alert('Please select at least one student to delete.');
        }
    });

    teacherFilter.addEventListener('change', filterTable);
    yearLevelFilter.addEventListener('change', filterTable);
    schoolYearFilter.addEventListener('change', filterTable);

    // Set initial filter values from URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search')) searchInput.value = urlParams.get('search');
    if (urlParams.has('user_id') && teacherFilter) teacherFilter.value = urlParams.get('user_id');
    if (urlParams.has('year_level_id')) yearLevelFilter.value = urlParams.get('year_level_id');
    if (urlParams.has('school_year_id')) schoolYearFilter.value = urlParams.get('school_year_id');
});
