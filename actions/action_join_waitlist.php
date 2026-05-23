<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/gymClass.class.php');
    require_once(__DIR__ . '/../database/enrollment.class.php');

    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit;
    }

    // TODO: this function is needed in almost every action file (create a helper function?)
    function redirectToPreviousPage() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Validate input
    $classId = $_POST['class_id'] ?? null;
    if (empty($classId)) {
        $session->addErrorMessage('Class ID is required.');
        redirectToPreviousPage();
    }

    if ($_POST['csrf_token'] !== $session->getCSRFToken()) {
        $session->addErrorMessage('Invalid CSRF token.');
        redirectToPreviousPage();
    }

    $classId = (int)$classId;
    $memberId = $session->getId();
    $db = getDatabaseConnection();

    try {
        Enrollment::addToWaitlist($db, $memberId, $classId);
        $session->addWarningMessage('The class is currently full. You have been added to the waitlist.');
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $session->addErrorMessage('Failed to join waitlist: ' . $e->getMessage());
    }

    redirectToPreviousPage();
?>