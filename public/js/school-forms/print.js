/**
 * Print functionality for School Form 10
 *
 * This script handles the print button functionality
 * for the School Form 10 (SF10-ES) page.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get the print button
    const printButton = document.getElementById('printButton');

    // Add click event listener to the print button
    if (printButton) {
        printButton.addEventListener('click', function() {
            // Trigger the browser's print dialog
            window.print();
        });
    }
});
