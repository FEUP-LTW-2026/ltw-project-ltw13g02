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
  require_once (__DIR__ . '/../utils/protected_file_guard.php');

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

  require_once (__DIR__ . '/../database/roleManager.class.php');
  $roleManager = new RoleManager();

  require_once (__DIR__ . '/../database/equipment.class.php');
  require_once (__DIR__ . '/../database/equipment_type.class.php');
  require_once (__DIR__ . '/../database/timeslot.class.php');
  require_once (__DIR__ . '/../database/equipment_status_manager.class.php');
  require_once (__DIR__ . '/../database/membership_tiers_perks_map.class.php');

  $equipmentTypes = EquipmentType::getAllEquipmentTypes($db);
  $equipmentItems = Equipment::getAllEquipment($db);
  $timeslots = Timeslot::getAllTimeslots($db);
  $statusManager = new EquipmentStatusManager();
  $perksMap = new MembershipTierPerksMap($db);

  require_once (__DIR__ . '/../templates/admin.php');

  echo '<script src="../javascript/adminNav.js" defer></script>';
  echo '<script src="../javascript/searchByUserName.js" defer></script>';
  echo '<script src="../javascript/addMembershipTier.js" defer></script>';
  echo '<script src="../javascript/equipmentAdmin.js" defer></script>';
  echo '<script src="../javascript/classCatalog.js" defer></script>';
  echo '<script src="../javascript/addInputFields.js" defer></script>';

  drawAdminPanel($users, $db, $session, $tiers, $roleManager, $equipmentTypes, $equipmentItems, $timeslots, $statusManager, $classesByWeekday, $trainers, $perksMap);
?>

<?php drawFooter() ?>

