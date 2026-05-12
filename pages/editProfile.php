<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../templates/common.php');
  require_once(__DIR__ . '/../templates/editProfile.php');

  drawHeader('Edit Profile', ['edit.css', 'profile.css']);

  drawEditProfilePage();

  drawFooter();
?>
