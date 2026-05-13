<?php 
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once (__DIR__ . '/../templates/common.php');
  require_once (__DIR__ . '/../templates/auth.php');

  drawHeader($session, 'Login', ['auth.css']);

  if ($session->isLoggedIn()) {
    header('Location: index.php');
  } else {
    drawLoginPage();
  }
  
  drawFooter();
?>
