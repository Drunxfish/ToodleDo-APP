// Retrieves selected task status and ID, then redirects to the same page with updated GET parameters
document.querySelectorAll('.taskStatusSelect').forEach(select => {

    // Save current option value
    let previousValue;
    select.addEventListener('focus', function () {
        previousValue = this.value;
    });

    
    // Redirects to the same page with updated GET parameters
    select.addEventListener('change', function () {
        let taskId = this.getAttribute('TSKIDXXXXXXXXXX');
        let status = this.value.toLowerCase();


        // Check if the user wants to delete the task
        if (status === 'delete') {
            if (!confirm('Are you sure you want to delete this task?')) {
                this.value = previousValue;
                return;
            }
        }

        // Retrieves current URL
        let url = new URL(window.location.href);

        // Set GET parameters
        url.searchParams.set('TSKIDXXXXXXXXXXXXXXXXXXXXXXXXXX', taskId);
        url.searchParams.set('TSKSTSXXXXXXXXXXXXXXXXXXXXXXXXXX', status);

        window.location.href = url; // refreshes page for PHP with new GET parameters
    });
});