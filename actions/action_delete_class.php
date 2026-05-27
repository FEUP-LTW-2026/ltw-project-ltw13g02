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

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }
    
    $db = getDatabaseConnection();

    $classTypeId = (int) $_POST['class_id'];

    try {
        ClassType::delete($db, $classTypeId);
        $session->addSuccessMessage('Class deleted successfully.');
        redirectToPreviousPage();
    } catch (Exception $e) {
        $session->addErrorMessage('Error deleting class: ' . $e->getMessage());
        redirectToPreviousPage();
    }
?>