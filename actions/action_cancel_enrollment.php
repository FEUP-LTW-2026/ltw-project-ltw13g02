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

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }

    // Validate input
    $classId = (int)$_POST['class_id'];
    if ($classId <= 0) {
        $session->addErrorMessage('Invalid class ID.');
        redirectToPreviousPage();
    }

    $memberId = $session->getId();
    $db = getDatabaseConnection();

    // Fetch class and its type
    $class = GymClass::getClassById($db, $classId);
    if ($class === null) {
        $session->addErrorMessage('Class not found.');
        redirectToPreviousPage();
    }

    try { // TODO: use try catch blocks in all actions main database operation in other actions
        Enrollment::cancelEnrollment($db, $memberId, $classId);
        $session->addSuccessMessage('Enrollment cancelled successfully.');
    } catch (PDOException $e) { // TODO: normalise error messages across the actions
        error_log($e->getMessage());
        $session->addErrorMessage('Failed to cancel enrollment: ' . $e->getMessage());
    }

    redirectToPreviousPage();
?>