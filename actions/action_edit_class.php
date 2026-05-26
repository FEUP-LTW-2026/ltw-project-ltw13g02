<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');
    require_once(__DIR__ . '/../database/trainer.class.php');

    $session = new Session();
    require_once(__DIR__ . '/../utils/role_change_guard.php');

    if (!$session->isLoggedIn() || !$session->isAdmin()) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    function redirectToPreviousPage() {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
        exit;
    }

    $required = ['name', 'description', 'trainer_id', 'capacity', 'start_time', 'end_time', 'weekday', 'csrf_token'];
    foreach ($required as $f) {
        if (empty($_POST[$f])) {
            $session->addErrorMessage('Please fill in all required fields.');
            redirectToPreviousPage();
        }
    }

    if ($_POST['csrf_token'] !== $session->getCSRFToken()) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }

    $classTypeId = (int) $_POST['class_id'];
    $name = trim((string) $_POST['name']);
    $description = trim((string) $_POST['description']);
    $trainerId = (int) $_POST['trainer_id'];
    $capacity = (int) $_POST['capacity'];
    $weekday = (string) $_POST['weekday'];
    $startTime = (string) $_POST['start_time']; // already in HH:MM format due to input type="time"
    $endTime = (string) $_POST['end_time'];

    $db = getDatabaseConnection();

    try {
        ClassType::update($db, $classTypeId, $name, $description, $trainerId, $capacity, $startTime, $endTime, $weekday);
        $session->addSuccessMessage('Class updated successfully.');
        redirectToPreviousPage();
    } catch (Exception $e) {
        $session->addErrorMessage('Error updating class: ' . $e->getMessage());
        redirectToPreviousPage();
    }
?>