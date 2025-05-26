document.addEventListener('DOMContentLoaded', function () {
    const alerts = ['success-alert', 'error-alert', 'validation-alert'];
    alerts.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            setTimeout(() => {
                element.classList.remove('animate__fadeInRight');
                element.classList.add('animate__fadeOutRight');
                setTimeout(() => element.remove(), 1000);
            }, 3000);
        }
    });
});

// Add this to your Alpine.js component or script
document.addEventListener('alpine:init', () => {
    Alpine.data('navigation', () => ({
        open: false,
        schoolManagementOpen: false
    }));
});
