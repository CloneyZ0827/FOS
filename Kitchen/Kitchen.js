document.addEventListener('DOMContentLoaded', () => {
    const statuses = ['Pending Order', 'Preparing Order', 'Ready to Serve', 'Done'];
    
    const nextButtons = document.querySelectorAll('.btn-forward');
    const backButtons = document.querySelectorAll('.btn-backward');
    const statusElements = document.querySelectorAll('.status');

    nextButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            let currentStatus = statusElements[index].innerText;
            let currentIndex = statuses.indexOf(currentStatus);

            if (currentIndex < statuses.length - 1) {
                statusElements[index].innerText = statuses[currentIndex + 1];
                
                // Check if the status is now "Done", if so, remove the row
                if (statuses[currentIndex + 1] === 'Done') {
                    const row = button.closest('tr'); // Get the current row
                    row.remove(); // Remove the entire row
                }
            }
        });
    });

    backButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            let currentStatus = statusElements[index].innerText;
            let currentIndex = statuses.indexOf(currentStatus);

            if (currentIndex > 0) {
                statusElements[index].innerText = statuses[currentIndex - 1];
            }
        });
    });
});
