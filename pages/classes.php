<?php
  declare(strict_types = 1);

  require_once (__DIR__ . '/../database/connection.php');
  require_once (__DIR__ . '/../database/class.class.php');
  require_once(__DIR__ . '/../utils/session.php');
  
  $session = new Session();

  require_once (__DIR__ . '/../templates/common.php');
  require_once (__DIR__ . '/../templates/classes.php');

  $db = getDatabeseConnection();

  $classes = GymClass::getAllClasses($db);

  drawHeader($session, 'Classes', ['classes.css']);

  drawIntro('WHAT WE OFFER', 'Our <b>classes</b>', '40+ weekly sessions across strength, cardio, flexibility and recovery. Something for every level.');

  drawClassesPage($classes);
  
  drawFooter(); 
?>
