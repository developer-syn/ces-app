function toggleTeacherFields() {
    const roleSelect = document.getElementById('role');
    const yearLevelField = document.getElementById('yearLevelField');
    const sectionField = document.getElementById('sectionField');
    const yearLevelInput = document.getElementById('year_level_id');
    const sectionInput = document.getElementById('section');

    if (roleSelect.value === 'teacher') {
        yearLevelField.style.display = 'block';
        sectionField.style.display = 'block';
        yearLevelInput.required = true;
        sectionInput.required = true;
    } else {
        yearLevelField.style.display = 'none';
        sectionField.style.display = 'none';
        yearLevelInput.required = false;
        sectionInput.required = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    toggleTeacherFields();

    // If there are old values and role was teacher, show fields
    if (document.getElementById('role').value === 'teacher') {
        document.getElementById('yearLevelField').style.display = 'block';
        document.getElementById('sectionField').style.display = 'block';
    }
});
