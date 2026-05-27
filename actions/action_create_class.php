<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');
    require_once(__DIR__ . '/../database/trainer.class.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    $session = new Session();
    require_once(__DIR__ . '/../utils/role_change_guard.php');

    if (!$session->isLoggedIn() || !$session->isAdmin()) {
        redirectToPreviousPage();
    }

    $required = ['name', 'description', 'trainer_id', 'capacity', 'start_time', 'end_time', 'weekday', 'csrf_token'];
    foreach ($required as $f) {
        if (empty($_POST[$f])) {
            $session->addErrorMessage('Please fill in all required fields.');
            redirectToPreviousPage();
        }
    }

    validateCSRFToken($_POST['csrf_token'], $session);

    $name = trim((string) $_POST['name']);
    $description = trim((string) $_POST['description']);
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
        } else {
            $session->addErrorMessage('Error creating class: ' . $e->getMessage());
        }
        redirectToPreviousPage();
    }
?>