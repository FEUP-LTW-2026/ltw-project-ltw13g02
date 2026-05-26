<?php 
  declare(strict_types=1);

  require_once (__DIR__ . '/../utils/session.php');
  require_once (__DIR__ . '/../database/connection.db.php');
  require_once (__DIR__ . '/../database/authenticated_user.class.php');
  require_once (__DIR__ . '/../database/classType.class.php');
  require_once (__DIR__ . '/../database/trainer.class.php');
  require_once (__DIR__ . '/../templates/common.php');
  require_once (__DIR__ . '/../database/membership_tier.class.php');
  require_once (__DIR__ . '/../database/roleManager.class.php');
  require_once (__DIR__ . '/../templates/admin.php');

  $session = new Session();
  $db = getDatabaseConnection();
  require_once (__DIR__ . '/../utils/role_change_guard.php');

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
  $classesByWeekday = ClassType::getClassesByWeekday($db);
  $trainers = Trainer::getAllTrainersBrief($db);

  // Passing 'admin.css' since we will add specific styling there
  drawHeader($session, 'Admin', ['admin.css']);
  drawIntro('ADMIN DASHBOARD', 'Manage your gym with ease', 'From equipment to classes, control everything in one place.');

  $tiers = MembershipTier::getAllMembershipTiers($db);

  $roleManager = new RoleManager();

  echo '<script src="../javascript/searchByUserName.js" defer></script>';
  echo '<script src="../javascript/addMembershipTier.js" defer></script>';
  echo '<script src="../javascript/classCatalog.js" defer></script>';

  drawAdminPanel($users, $db, $tiers, $roleManager, $classesByWeekday, $trainers, $session);
?>

<?php drawFooter() ?>

