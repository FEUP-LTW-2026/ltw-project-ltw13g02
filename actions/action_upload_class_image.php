<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/protected_file_guard.php');

    if (!$session->isLoggedIn() || !$session->isAdmin()) {
        redirectToLogin();
    }

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }

    $classTypeId = (int) ($_POST['class_id'] ?? 0);
    if ($classTypeId <= 0) {
        $session->addErrorMessage('Invalid class ID.');
        redirectToPreviousPage();
    }

    if (!isset($_FILES['class-image']) || $_FILES['class-image']['error'] !== UPLOAD_ERR_OK) {
        $error = $_FILES['class-image']['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($error === UPLOAD_ERR_INI_SIZE || $error === UPLOAD_ERR_FORM_SIZE) {
            $session->addErrorMessage('File size exceeds the server limit. Please upload a smaller image.');
        } else {
            $session->addErrorMessage('Error uploading file. Please try again.');
        }
        redirectToPreviousPage();
    }

    saveClassImage($classTypeId);

    $session->addSuccessMessage('Class image updated successfully.');
    redirectToPreviousPage();
?>
