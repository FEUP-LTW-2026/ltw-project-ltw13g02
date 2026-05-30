<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../../../utils/session.php');
    require_once(__DIR__ . '/../../../database/connection.db.php');
    require_once(__DIR__ . '/../../../utils/helpers.php');

    $session = new Session();

    require_once(__DIR__ . '/../../../utils/protected_file_guard.php');

    if (!$session->isLoggedIn() || !$session->isAdmin()) {
        redirectToLogin();
    }

    $required = ['tier_id', 'csrf_token'];
    if (!validateRequiredFields($required)) {
        $session->addErrorMessage('All fields are required.');
        redirectToPreviousPage();
    }

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }
    
    $tierId = (int)$_POST['tier_id'];
    if ($tierId <= 0) {
        $session->addErrorMessage('Invalid membership tier ID.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    try {
        $db->beginTransaction();
        $stmt = $db->prepare('SELECT is_active FROM MembershipTier WHERE id = ?');
        $stmt->execute([$tierId]);
        $tier = $stmt->fetch();

        if (!$tier) {
            $session->addErrorMessage('Membership tier not found.');
            redirectToPreviousPage();
        }

        if ((bool)$tier['is_active']) {
            $session->addErrorMessage('Active membership tiers cannot be deleted. Disable the tier first.');
            redirectToPreviousPage();
        }

        $stmt = $db->prepare('DELETE FROM MembershipTier WHERE id = ?');
        $stmt->execute([$tierId]);

        $db->commit();
        $session->addSuccessMessage('Membership tier deleted successfully.');
    } catch (PDOException $e) {
        if ($db->inTransaction()) $db->rollBack();
        $session->addErrorMessage($e->getMessage());
        $session->addErrorMessage('Database error occurred while deleting the tier.');
    }

    redirectToPreviousPage();
?>