<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if(!$session->isLoggedIn()) {
    header('Location: login.php');
  } else {
    require_once (__DIR__ . '/../templates/common.php');
    require_once (__DIR__ . '/../templates/membership_terminal.php');

    require_once (__DIR__ . '/../templates/index.php');

    drawHeader($session, 'Membership Selection', ['membership_terminal.css', 'index.css']);

    drawIntro('MEMBERSHIP TERMINAL', 'Select your membership', 'Choose the type of membership you want to purchase.');

    require_once (__DIR__ . '/../database/connection.db.php');
    $db = getDatabaseConnection();

    require_once (__DIR__ . '/../database/membership_tier.class.php');
    $plans = MembershipTier::getMembershipTiers($db);

    $plan_id = (int) $_POST['plan_id'];

    drawMembershipTerminalForm($plans, $plan_id, $session);

    drawFooter();
  }
?>