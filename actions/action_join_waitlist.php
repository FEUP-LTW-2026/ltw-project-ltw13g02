<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/gymClass.class.php');
    require_once(__DIR__ . '/../database/enrollment.class.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/role_change_guard.php');

    if (!$session->isLoggedIn()) {
        redirectToLogin();
    }

    if(empty($_POST['class_id'])) {
        $session->addErrorMessage('Class ID is required.');
        redirectToPreviousPage();
    }

    if(!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token.');
        redirectToPreviousPage();
    }

    $classId = (int)$_POST['class_id'];
    if ($classId <= 0) {
        $session->addErrorMessage('Invalid class ID.');
        redirectToPreviousPage();
    }

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