<?php
  declare(strict_types = 1);

  require_once (__DIR__ . '/../templates/common.php');
  require_once (__DIR__ . '/../templates/auth.php');

  drawHeader('Sign Up');

  drawSignupPage();

  drawFooter();
?>
