<?php 
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once (__DIR__ . '/../templates/common.php');
    require_once (__DIR__ . '/../templates/membership_terminal.php');
    require_once (__DIR__ . '/../templates/index.php');
    require_once (__DIR__ . '/../database/connection.db.php');
    require_once (__DIR__ . '/../database/membership_tier.class.php');
    require_once (__DIR__ . '/../database/membership_tiers_perks_map.class.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/protected_file_guard.php');

    if(!$session->isLoggedIn()) {
    header('Location: login.php');
    } else {


    drawHeader($session, 'Membership Selection', ['membership_terminal.css', 'index.css']);

    drawIntro('MEMBERSHIP TERMINAL', 'Select your membership', 'Choose the type of membership you want to purchase.');

    $db = getDatabaseConnection();

    $plans = MembershipTier::getActiveMembershipTiers($db);

    $perksMap = new MembershipTierPerksMap($db);

    $planId = (int) $_POST['plan_id'];

    drawMembershipTerminalForm($plans, $planId, $session, $perksMap, $db);

    drawFooter();
    }
?>

<script src="../javascript/updateCheckoutSelection.js" defer></script>
