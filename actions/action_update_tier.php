<?php 
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../utils/helpers.php');
    require_once(__DIR__ . '/../database/membership_tier.class.php');
    require_once(__DIR__ . '/../database/membership_tier_perk.class.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/protected_file_guard.php');

    if(!$session->isLoggedIn() || !$session->isAdmin()) {
        redirectToLogin();
    }

    if (empty($_POST['tier_id']) || empty($_POST['name']) || empty($_POST['price']) || empty($_POST['booking_window_days'])) {
        $session->addErrorMessage('Invalid request. Please fill in all required fields.');
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

    $name = trim($_POST['name']);
    if(!validateText($name)) {
        $session->addErrorMessage('Invalid name. Please use only letters and spaces.');
        redirectToPreviousPage();
    }

    $price = (float) $_POST['price'];
    if($price < 0) {
        $session->addErrorMessage('Invalid price. Please enter a non-negative value.');
        redirectToPreviousPage();
    }

    $bookingWindowDays = (int) $_POST['booking_window_days'];
    if($bookingWindowDays < 0) {
        $session->addErrorMessage('Invalid booking window. Please enter a non-negative integer.');
        redirectToPreviousPage();
    }

    $perks = [];
    foreach ($_POST['perks'] as $perk) {
        $perk = trim((string)$perk);
        if (!validateTextBox($perk)) {
            $session->addErrorMessage('Invalid perk description. Please use only letters, numbers, spaces, and basic punctuation, and limit to 500 characters.');
            redirectToPreviousPage();
        }
        $perks[] = $perk;
    }


    $db = getDatabaseConnection();
    

    try {
        $db->beginTransaction();
        $membership = MembershipTier::getMembershipTierById($db, $tierId);
        if($membership){
            $membership->setName($name);
            $membership->setMonthlyFee($price);
            $membership->setBookingWindowDays($bookingWindowDays);
            $membership->save($db);
        }
        // Delete existing perks
        MembershipTierPerk::deleteByMembershipTierId($db, $tierId);

        // Insert new perks
        foreach ($perks as $perk) {
            MembershipTierPerk::create($db, $tierId, $perk);
        }
        $db->commit();
        $session->addSuccessMessage('Membership tier updated successfully.');
    } catch (PDOException $e) {
        if($db->inTransaction()) {
            $db->rollBack();
        }
        $session->addErrorMessage('Database error occurred while updating the tier.');
    }

    redirectToPreviousPage();
?>