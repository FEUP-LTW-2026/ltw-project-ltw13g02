  const dialogCreate = document.getElementById('dialog-create-class');
  const dialogEdit = document.getElementById('dialog-edit-class');
  
// Opens "Add Class" Modal
const btnAddClass = document.getElementById('add-class-btn');
if (btnAddClass && dialogCreate) {
    btnAddClass.addEventListener('click', () => {
        dialogCreate.showModal();
    });
}

// Opens "Add Class" Modal
const btnEditClasses = document.querySelectorAll('.btn-edit-class');
btnEditClasses.forEach(btn => {
    btn.addEventListener('click', (e) => {
        const classId = e.target.getAttribute('data-id'); // The id of the class to edit
        
        if (dialogEdit) {
            // Populate the form fields based on the class ID
            document.getElementById('edit-class-id').value = classId;
            //_--
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