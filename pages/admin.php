<?php 
  declare(strict_types=1);

  require_once (__DIR__ . '/../utils/session.php');
  $session = new Session();
  require_once (__DIR__ . '/../utils/role_change_guard.php');

  require_once (__DIR__ . '/../database/connection.db.php');
  $db = getDatabaseConnection();

  require_once (__DIR__ . '/../database/authenticated_user.class.php');
  $user = AuthenticatedUser::getUser($db, $session->getId());

  if (!$session->isLoggedIn()) {
    header('Location: login.php');
    exit;
  }

  if (!$session->isAdmin()) {
    header('Location: index.php');
    exit;
  }

  // Get all existing users 
  $users = AuthenticatedUser::getAllUsers($db);

  require_once (__DIR__ . '/../templates/common.php');
  
  // Passing 'admin.css' since we will add specific styling there
  drawHeader($session, 'Admin', ['admin.css']);
  drawIntro('ADMIN DASHBOARD', 'Manage your gym with ease', 'From equipment to classes, control everything in one place.');

  require_once (__DIR__ . '/../database/membership_tier.class.php');
  $tiers = MembershipTier::getMembershipTiers($db);

  require_once (__DIR__ . '/../database/roleManager.class.php');
  $roleManager = new RoleManager();

  require_once (__DIR__ . '/../templates/admin.php');
  drawAdminPanel($users, $db, $session, $tiers, $roleManager);
?>

<?php drawFooter() ?>