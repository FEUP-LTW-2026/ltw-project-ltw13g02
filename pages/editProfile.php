<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../templates/common.php');
  require_once(__DIR__ . '/../templates/editProfile.php');

  drawHeader($session, 'Edit Profile', ['edit.css', 'profile.css']);

  drawEditProfilePage();

  drawFooter();
?>
