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

    if (empty($_POST['tier_id'])) {
        $session->addErrorMessage('Invalid request.');
        redirectToPreviousPage();
    }

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }

    $tierId = (int) $_POST['tier_id'];
    if($tierId <= 0) {
        $session->addErrorMessage('Invalid membership tier ID.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    try {
        $stmt = $db->prepare('UPDATE MembershipTier SET is_active = 0 WHERE id = ?');
        $stmt->execute([$tierId]);

        $session->addSuccessMessage('Membership tier disabled successfully. Existing memberships were preserved.');
    } catch (PDOException $e) {
        $session->addErrorMessage('Database error occurred while disabling the tier.');
    }

    redirectToPreviousPage();
?>