const dialogCreate = document.getElementById('dialog-create-class');
const dialogEdit = document.getElementById('dialog-edit-class');

// Opens "Add User" Modal
const btnAddUser = document.getElementById('add-user-btn');
const dialogAddUser = document.getElementById('dialog-add-user');
if (btnAddUser && dialogAddUser) {
    btnAddUser.addEventListener('click', () => {
        dialogAddUser.showModal();
    });
}

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
            // Get data from button attributes\
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

            // Load current class image into preview and set class_id on the upload form
            if (editImgPreview) {
                editImgPreview.src = `../img/classes/${classId}.jpg`;
                editImgPreview.onerror = () => { editImgPreview.src = '../img/classes/default.jpg'; };
            }
            document.getElementById('edit-class-upload-id').value = classId;

            dialogEdit.showModal();
        }
    });
});

// Image upload — Create dialog (preview only, submits with the main form)
const createUploadBtn   = document.getElementById('create-class-upload-btn');
const createImageInput  = document.getElementById('create-class-image-input');
const createImgPreview  = document.getElementById('create-class-img-preview');
if (createUploadBtn && createImageInput && createImgPreview) {
    createUploadBtn.addEventListener('click', () => createImageInput.click());
    createImageInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            createImgPreview.src = URL.createObjectURL(this.files[0]);
        }
    });
}

// Image upload — Edit dialog (auto-submits its own form, same as editProfile.js)
const editUploadBtn    = document.getElementById('edit-class-upload-btn');
const editImageInput   = document.getElementById('edit-class-image-input');
const editImgPreview   = document.getElementById('edit-class-img-preview');
const editUploadForm   = document.getElementById('edit-class-upload-form');
if (editUploadBtn && editImageInput && editUploadForm) {
    editUploadBtn.addEventListener('click', () => editImageInput.click());
    editImageInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            editUploadForm.submit();
        }
    });
}

// Closes modals when the "Cancel" button is clicked
const closeButtons = document.querySelectorAll('.btn-close-dialog');
    closeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const dialog = btn.closest('dialog');  // Finds the parent dialog of the clicked button
        if (dialog) dialog.close(); // Closes the dialog
    });
});