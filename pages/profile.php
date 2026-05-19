<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) {
    header('Location: login.php');

  } else {
    require_once(__DIR__ . '/../templates/common.php');
    require_once(__DIR__ . '/../templates/profile.php');
    require_once(__DIR__ . '/../templates/classes.php');
    require_once (__DIR__ . '/../database/connection.db.php');
    require_once (__DIR__ . '/../database/class.class.php');
    require_once (__DIR__ . '/../database/authenticated_user.class.php');


    $db = getDatabaseConnection();
    $user = AuthenticatedUser::getUser($db, $session->getId());

    if (!$user) {
      $session->logout();
      $session->addErrorMessage('User not found. Please log in again.');
      header('Location: login.php');
      exit;
    }

    $name = $user->getFullName();
    $username = $user->getUsername();
    $classes = GymClass::getEnrolledClasses($db, $session->getId());

    drawHeader($session, 'Profile', ['profile.css']);

    drawProfilePage($name, $username, $classes);

    drawFooter();
  }
?>
