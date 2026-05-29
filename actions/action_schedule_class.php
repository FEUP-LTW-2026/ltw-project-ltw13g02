<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/trainer.class.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    $session = new Session();
    require_once(__DIR__ . '../../utils/protected_file_guard.php');

    if (!$session->isLoggedIn()) {
        redirectToLogin();
    }

    if (!$session->isTrainer()) {
        $session->addErrorMessage('Unauthorized action.');
        redirectToPreviousPage();
    }

    if(empty($_POST['class_type_id']) || empty($_POST['start_timestamp']) || empty($_POST['end_timestamp'])) {
        $session->addErrorMessage('All fields are required.');
        redirectToPreviousPage();
    }

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token.');
        redirectToPreviousPage();
    }

    $classTypeId = (int)$_POST['class_type_id'];
    if ($classTypeId <= 0) {
        $session->addErrorMessage('Invalid class type ID.');
        redirectToPreviousPage();
    }

    $nextStartTimestamp = $_POST['start_timestamp'];
    // TODO: Validate timestamp format
    $nextEndTimestamp = $_POST['end_timestamp'] ?? null;
    // TODO: Validate timestamp format

    $db = getDatabaseConnection();

    try {
        GymClass::scheduleClass($db, $classTypeId, $nextStartTimestamp, $nextEndTimestamp);
        $session->addSuccessMessage('Class scheduled successfully.');
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $session->addErrorMessage('Failed to schedule class.');
    }

    redirectToPreviousPage();
?>