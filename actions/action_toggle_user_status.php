<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/role_change_guard.php');

    if (!$session->isLoggedIn() || !$session->isAdmin()) {
        redirectToLogin();
    }

    if (empty($_POST['user_id']) || !isset($_POST['is_active'])) {
        $session->addErrorMessage('Invalid request.');
        redirectToPreviousPage();
    }

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token.');
        redirectToPreviousPage();
    }

    $userId = (int) $_POST['user_id'];
    if ($userId <= 0) {
        $session->addErrorMessage('Invalid user ID.');
        redirectToPreviousPage();
    }

    $isActive = (int) $_POST['is_active'];
    if ($isActive !== 0 && $isActive !== 1) {
        $session->addErrorMessage('Invalid status value.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    try {
        $stmt = $db->prepare('UPDATE User SET is_active = ? WHERE id = ?');
        $stmt->execute([$isActive, $userId]);
        $session->addSuccessMessage('User status updated successfully.');
    } catch (PDOException $e) {
        $session->addErrorMessage('Database error occurred.');
    }

    redirectToPreviousPage();
?>