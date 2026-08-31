// Calculate age from birthdate
function calculateAge() {
    const birthdateInput = document.getElementById('birthdate');
    const ageInput = document.getElementById('age');
    const birthdate = birthdateInput.value;

    if (!birthdate) {
        ageInput.value = '';
        return;
    }

    const birthDate = new Date(birthdate);
    const today = new Date();

    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDifference = today.getMonth() - birthDate.getMonth();

    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    ageInput.value = age;
}

// Form validation and error focus
document.addEventListener('DOMContentLoaded', function () {
    // Focus on first error field if any
    const firstErrorField = document.querySelector('.text-red-600');
    if (firstErrorField) {
        const inputId = firstErrorField.getAttribute('data-input-id') ||
            firstErrorField.previousElementSibling.querySelector('input, select')?.id;
        if (inputId) {
            const inputElement = document.getElementById(inputId);
            if (inputElement) {
                inputElement.focus();
                inputElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }
    }

    // Age validation on form submit
    const form = document.getElementById('studentForm');
    form.addEventListener('submit', function (event) {
        const ageInput = document.getElementById('age');
        const age = parseInt(ageInput.value);

        if (age < 5 || age > 60) {
            event.preventDefault();
            const errorElement = document.querySelector('[data-input-id="age"]') ||
                ageInput.nextElementSibling;
            if (errorElement) {
                errorElement.textContent = "Age must be between 5 and 60 years";
                errorElement.classList.remove('hidden');
            }
            ageInput.classList.add('border-red-500');
            ageInput.focus();
            ageInput.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            return false;
        }

        return true;
    });
});
