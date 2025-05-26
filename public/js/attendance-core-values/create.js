document.addEventListener('DOMContentLoaded', function () {
    // Get all month day inputs
    const dayInputs = document.querySelectorAll('input[id$="_days"]');

    dayInputs.forEach(dayInput => {
        const month = dayInput.id.replace('_days', '');
        const presentInput = document.getElementById(`${month}_present`);

        // Update present input max when days input changes
        dayInput.addEventListener('input', function () {
            presentInput.max = this.value;

            // If present value exceeds new max, adjust it
            if (presentInput.value > this.value) {
                presentInput.value = this.value;
            }
        });

        // Initialize max value on page load
        if (dayInput.value) {
            presentInput.max = dayInput.value;
        }

        // Validate present input when it changes
        presentInput.addEventListener('input', function () {
            const maxDays = parseInt(dayInput.value) || 0;
            if (this.value > maxDays) {
                this.value = maxDays;
            }
            if (this.value < 0) {
                this.value = 0;
            }
        });
    });
});
