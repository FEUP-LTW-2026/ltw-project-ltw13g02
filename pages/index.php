<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once (__DIR__ . '/../templates/common.php');
  require_once (__DIR__ . '/../templates/index.php');

  require_once (__DIR__ . '/../database/connection.db.php');
  $db = getDatabaseConnection();

  require_once (__DIR__ . '/../database/membership_tier.class.php');
  $plans = MembershipTier::getMembershipTiers($db);

  drawHeader($session, 'Home', ['index.css']);
  
  drawIndexPage($plans);

  drawFooter();
?>