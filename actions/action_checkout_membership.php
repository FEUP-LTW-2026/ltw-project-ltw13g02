<?php 
    declare(strict_types=1); 

    require_once(__DIR__ . '/../utils/session.php');
    require_once (__DIR__ . '/../database/connection.db.php');
    require_once (__DIR__ . '/../database/membership_tier.class.php');
    require_once (__DIR__ . '/../database/authenticated_user.class.php');
    require_once (__DIR__ . '/../database/roleManager.class.php');
    require_once (__DIR__ . '/../utils/helpers.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/protected_file_guard.php');
    
    if(!$session->isLoggedIn()) {
        redirectToLogin();
    }

    if(empty($_POST['tier_id']) || empty($_POST['duration']) || empty($_POST['payment_type'])) {
        $session->addErrorMessage('All fields are required.');
        redirectToPreviousPage();
    }

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }

    $tier_id = (int) $_POST['tier_id'];
    if($tier_id <= 0) {
        $session->addErrorMessage('Invalid membership tier ID.');
        redirectToPreviousPage();
    }
    
    $duration = (int) $_POST['duration'];
    if($duration <= 0) {
        $session->addErrorMessage('Invalid membership duration.');
        redirectToPreviousPage();
    }

    $payment_type = $_POST['payment_type'];
    if(!validateText($payment_type)) {
        $session->addErrorMessage('Invalid payment type.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    try {
        $tier = MembershipTier::getMembershipTierById($db, $tier_id);
    } catch (Exception $e) {
        $session->addErrorMessage('Selected membership tier does not exist.');
        redirectToPreviousPage();
    }

    try {
        MembershipTier::createMembership($db, $session->getId(), $tier_id, $tier->getMonthlyFee(), $duration, $payment_type);
    } catch (Exception $e) {
        $session->addErrorMessage($e->getMessage());
        redirectToPreviousPage();
    }

    // Update the session role
    $session->setRole('Member');

    $session->addSuccessMessage('Membership created successfully!');
    redirectToHomePage();
?>