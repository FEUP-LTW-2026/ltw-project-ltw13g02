<?php 
    declare(strict_types=1); 

    require_once(__DIR__ . '/../../utils/session.php');
    require_once (__DIR__ . '/../../database/connection.db.php');
    require_once (__DIR__ . '/../../utils/helpers.php');
    require_once (__DIR__ . '/../../database/membership.class.php');

    $session = new Session();

    require_once(__DIR__ . '/../../utils/protected_file_guard.php');
    
    if(!$session->isLoggedIn()) {
        redirectToLogin();
    }

    $required = ['membership_id', 'csrf_token'];
    if (!validateRequiredFields($required)) {
        $session->addErrorMessage('All fields are required.');
        redirectToPreviousPage();
    }

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();
    $membershipId = (int)$_POST['membership_id'];
    $nextTierId = (int)$_POST['next_tier_id'];

    if (!Membership::belongsToMember($db, $membershipId, (int)$session->getId())) {
        $session->addErrorMessage('You do not have permission to upgrade this membership.');
        redirectToPreviousPage();
    }

    if (!Membership::isNextTierValid($db, $membershipId, $nextTierId)) {
        $session->addErrorMessage('Invalid membership tier selected for upgrade.');
        redirectToPreviousPage();
    }

    try {
        Membership::upgradeMembership($db, $membershipId, $nextTierId);
        $session->addSuccessMessage('Membership upgraded successfully.');
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $session->addErrorMessage('Failed to upgrade membership: ' . $e->getMessage());
    }

    redirectToPreviousPage();
?>