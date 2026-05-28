<?php 
    declare(strict_types=1); 

    require_once(__DIR__ . '/../utils/session.php');
    require_once (__DIR__ . '/../database/connection.db.php');
    require_once (__DIR__ . '/../utils/helpers.php');
    require_once (__DIR__ . '/../database/membership.class.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/role_change_guard.php');
    
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

    try {
        Membership::payMembership($db, $membershipId);
        $session->addSuccessMessage('Membership paid successfully.');
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $session->addErrorMessage('Failed to pay membership: ' . $e->getMessage());
    }

    redirectToPreviousPage();
?>