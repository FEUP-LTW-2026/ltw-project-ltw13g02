<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once (__DIR__ . '/../templates/common.php');
    require_once (__DIR__ . '/../templates/index.php');
    require_once (__DIR__ . '/../database/connection.db.php');
    require_once (__DIR__ . '/../database/membership_tier.class.php');
    require_once (__DIR__ . '/../database/membership_tiers_perks_map.class.php');

    $session = new Session();

    $db = getDatabaseConnection();

    $plans = MembershipTier::getActiveMembershipTiers($db);

    $perksMap = new MembershipTierPerksMap($db);

    drawHeader($session, 'Home', ['index.css']);

    drawIndexPage($plans, $session, $perksMap);

    drawFooter();
?>