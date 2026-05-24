<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/trainer.class.php');

    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit;
    }

    function redirectToPreviousPage(): void {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/index.php'));
        exit;
    }

    if (!$session->isTrainer()) {
        $session->addErrorMessage('Unauthorized action.');
        redirectToPreviousPage();
    }

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $session->getCSRFToken()) {
        $session->addErrorMessage('Invalid CSRF token.');
        redirectToPreviousPage();
    }

    $classTypeId = (int)($_POST['class_type_id'] ?? 0);
    $nextStartTimestamp = $_POST['start_timestamp'] ?? null;
    $nextEndTimestamp = $_POST['end_timestamp'] ?? null;

    if ($classTypeId <= 0 || !$nextStartTimestamp || !$nextEndTimestamp) {
        $session->addErrorMessage('All fields are required.');
        redirectToPreviousPage();
    }

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