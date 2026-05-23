<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();
  require_once(__DIR__ . '/../utils/role_change_guard.php');

  if (!$session->isLoggedIn()) {
    header('Location: login.php');
  
  } else {
    require_once(__DIR__ . '/../templates/common.php');
    require_once(__DIR__ . '/../templates/editProfile.php');
    require_once (__DIR__ . '/../database/connection.db.php');
    require_once (__DIR__ . '/../database/authenticated_user.class.php');

    $db = getDatabaseConnection();
    $user = AuthenticatedUser::getUser($db, $session->getId());

    if (!$user) {
      $session->logout();
      $session->addErrorMessage('User not found. Please log in again.');
      header('Location: login.php');
      exit;
    }

    $firstName = $user->getFirstName();
    $lastName = $user->getLastName();
    $username = $user->getUsername();

    drawHeader($session, 'Edit Profile', ['profile.css', 'edit.css']);

    drawEditProfilePage($session->getId(), $firstName, $lastName, $username);

    drawFooter();
  }
?>
