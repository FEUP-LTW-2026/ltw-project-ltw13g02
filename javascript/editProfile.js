// To merge both the input image and the submit button into a single button inside editProfile.php.

document.addEventListener('DOMContentLoaded', function() {
  const uploadBtn = document.getElementById('upload-btn');
  const imageInput = document.getElementById('image-input');
  const uploadForm = document.getElementById('upload-form');

  if (uploadBtn && imageInput && uploadForm) {
    uploadBtn.addEventListener('click', function() {
      imageInput.click(); // clicks on the hidden file input
    });

    imageInput.addEventListener('change', function() {
      if (this.files.length > 0) {
        uploadForm.submit(); // submites the form when a file is selected
      }
    });
  }
});