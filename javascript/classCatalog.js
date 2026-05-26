  const dialogCreate = document.getElementById('dialog-create-class');
  const dialogEdit = document.getElementById('dialog-edit-class');
  
// Opens "Add Class" Modal
const btnAddClass = document.getElementById('add-class-btn');
if (btnAddClass && dialogCreate) {
    btnAddClass.addEventListener('click', () => {
        dialogCreate.showModal();
    });
}

// Opens "Edit Class" Modal
const btnEditClasses = document.querySelectorAll('.btn-edit-class');
btnEditClasses.forEach(btn => {
    btn.addEventListener('click', (e) => {
        
        if (dialogEdit) {
            // Get data from button attributes
            const classId = e.target.getAttribute('data-id');
            const className = e.target.getAttribute('data-name');
            const classDescription = e.target.getAttribute('data-description');
            const trainerId = e.target.getAttribute('data-trainer-id');
            const capacity = e.target.getAttribute('data-capacity');
            const weekday = e.target.getAttribute('data-weekday');
            const startTime = e.target.getAttribute('data-start-time');
            const endTime = e.target.getAttribute('data-end-time');

            // Populate text and number inputs
            document.getElementById('edit-class-id').value = classId;
            document.getElementById('edit-class-name').value = className;
            document.getElementById('edit-class-description').value = classDescription;
            document.getElementById('edit-class-capacity').value = capacity;
            document.getElementById('edit-class-start-time').value = startTime;
            document.getElementById('edit-class-end-time').value = endTime;

            // Set trainer select value
            const trainerSelect = document.getElementById('edit-class-trainer');
            trainerSelect.value = trainerId;

            // Set weekday select value
            const weekdaySelect = document.getElementById('edit-class-weekday');
            weekdaySelect.value = weekday;

            dialogEdit.showModal();
        }
    });
});

// Closes modals when the "Cancel" button is clicked
const closeButtons = document.querySelectorAll('.btn-close-dialog');
    closeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const dialog = btn.closest('dialog');  // Finds the parent dialog of the clicked button
        if (dialog) dialog.close(); // Closes the dialog
    });
});