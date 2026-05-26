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
            $db->rollBack();
            $session->addErrorMessage('Membership tier not found.');
            redirectToPreviousPage();
        }

        if ((bool)$tier['is_active']) {
            $db->rollBack();
            $session->addErrorMessage('Active membership tiers cannot be deleted. Disable the tier first.');
            redirectToPreviousPage();
        }

        // Checks if any memberships are still referencing this tier
        $stmt = $db->prepare('SELECT COUNT(*) FROM Membership WHERE membership_tier_id = ?');
        $stmt->execute([$tierId]);
        $membershipCount = (int)$stmt->fetchColumn();

        if ($membershipCount > 0) {
            $db->rollBack();
            $session->addErrorMessage('This membership tier cannot be deleted because it is still referenced by memberships.');
            redirectToPreviousPage();
        }

        $stmt = $db->prepare('DELETE FROM MembershipTier WHERE id = ?');
        $stmt->execute([$tierId]);

        $db->commit();

        $session->addSuccessMessage('Membership tier deleted successfully.');
    } catch (PDOException $e) {
        if ($db->inTransaction()) $db->rollBack();
        $session->addErrorMessage('Database error occurred while deleting the tier.');
    }

    redirectToPreviousPage();
?>