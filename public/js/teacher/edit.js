function toggleTeacherFields() {
    const roleSelect = document.getElementById("role");
    const yearLevelField = document.getElementById("yearLevelField");
    const sectionField = document.getElementById("sectionField");
    const yearLevelInput = document.getElementById("year_level_id");
    const sectionInput = document.getElementById("section");

    if (roleSelect.value === "teacher") {
        yearLevelField.style.display = "block";
        sectionField.style.display = "block";
        yearLevelInput.required = true;
        sectionInput.required = true;

        // Optional: Focus on section field when shown
        if (sectionField.style.display === "block") {
            sectionInput.focus();
        }
    } else {
        yearLevelField.style.display = "none";
        sectionField.style.display = "none";
        yearLevelInput.required = false;
        sectionInput.required = false;
    }
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
    toggleTeacherFields();
});

function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
    const eye = document.getElementById("eye-" + fieldId);
    if (input.type === "password") {
        input.type = "text";
        eye.classList.add("text-indigo-600");
    } else {
        input.type = "password";
        eye.classList.remove("text-indigo-600");
    }
}
