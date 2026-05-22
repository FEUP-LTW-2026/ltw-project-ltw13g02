<?php 
  declare(strict_types=1);
?>

<?php 
  require_once (__DIR__ . '/../utils/session.php');
  $session = new Session();

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

  if(!isset($_POST['user_id'], $_POST['new_role_table'])) {
    header('Location: ../pages/admin.php');
    exit;
  }

  $userId = intval($_POST['user_id']);
  $userName = $_POST['user_name'];
  $oldRole = $_POST['old_role_table'];
  $newRole = $_POST['new_role_table'];

  require_once (__DIR__ . '/../database/roleManager.class.php');
  RoleManager::changeUserRole($db, $userId, $oldRole, $newRole, $session);
?>