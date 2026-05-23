<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    $session = new Session();

    if (!$session->isLoggedIn() || !$session->isAdmin()) {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php'));
        exit;
    }

    function redirectToPreviousPage() {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
        exit;
    }

    if (empty($_POST['tier_id'])) {
        $session->addErrorMessage('Invalid request.');
        redirectToPreviousPage();
    }

    if ($_POST['csrf_token'] !== $session->getCSRFToken()) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();
    $tierId = (int) $_POST['tier_id'];

    try {
        $stmt = $db->prepare('UPDATE MembershipTier SET is_active = 1 WHERE id = ?');
        $stmt->execute([$tierId]);

        $session->addSuccessMessage('Membership tier enabled successfully.');
        redirectToPreviousPage();
    } catch (PDOException $e) {
        $session->addErrorMessage('Database error occurred while enabling the tier.');
        redirectToPreviousPage();
    }
?>