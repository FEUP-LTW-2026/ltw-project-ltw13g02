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
    if ($classTypeId <= 0) {
        $session->addErrorMessage('Class type is required.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    $nextClass = Trainer::getNextClassToScheduleByTypeId($db, $session->getId(), $classTypeId);

    if ($nextClass === null) {
        $session->addErrorMessage('No upcoming class found to schedule.');
        redirectToPreviousPage();
    }

    $nextStartTimestamp = $nextClass->startTimestamp;
    $nextEndTimestamp = $nextClass->endTimestamp;

    $stmt = $db->prepare('
        INSERT INTO Class (class_type_id, start_timestamp, end_timestamp, status)
        VALUES (?, ?, ?, "scheduled")
    ');

    try {
        $stmt->execute([$classTypeId, $nextStartTimestamp, $nextEndTimestamp]);
        $session->addSuccessMessage('Class scheduled successfully.');
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $session->addErrorMessage('Failed to schedule class.');
    }

    redirectToPreviousPage();
?>