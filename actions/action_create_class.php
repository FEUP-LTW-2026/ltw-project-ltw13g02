<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');
    require_once(__DIR__ . '/../database/trainer.class.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    $session = new Session();
    require_once(__DIR__ . '/../utils/protected_file_guard.php');

    if (!$session->isLoggedIn() || !$session->isAdmin()) {
        redirectToPreviousPage();
    }

    $required = ['name', 'description', 'trainer_id', 'capacity', 'weekday', 'start_time', 'end_time', 'csrf_token'];
    validateRequiredFields($required);

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }
    
    $name = trim((string) $_POST['name']);

    if (!validateTextBox($name)) {
        $session->addErrorMessage('Class name can only contain letters, numbers and spaces.');
        redirectToPreviousPage();
    }

    $description = trim((string) $_POST['description']);

    if (!validateTextBox($description)) {
        $session->addErrorMessage('Class description can only contain letters, numbers and spaces.');
        redirectToPreviousPage();
    }

    $trainerId = (int) $_POST['trainer_id'];
    $capacity = (int) $_POST['capacity'];
    $weekday = (int) $_POST['weekday'];
    $startTime = (string) $_POST['start_time']; // already in HH:MM format due to input type="time"
    $endTime = (string) $_POST['end_time'];

    $db = getDatabaseConnection();

    try {
        ClassType::create($db, $name, $description, $trainerId, $capacity, $startTime, $endTime, $weekday);
        $session->addSuccessMessage('Class created successfully.');
        redirectToPreviousPage();
    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), 'Cannot insert overlapping classes for the same trainer.')) {
            $session->addErrorMessage('Error creating class: This class overlaps with another class for the same trainer.');
        } else if (str_contains($e->getMessage(), 'CHECK constraint failed: ValidTimes')) {
            $session->addErrorMessage('Error creating class: Start time must be before end time.');
        } else {
            $session->addErrorMessage('Error creating class: ' . $e->getMessage());
        }
        redirectToPreviousPage();
    }
?>