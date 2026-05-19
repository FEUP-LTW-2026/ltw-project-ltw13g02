<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');

  $session = new Session();

  require_once (__DIR__ . '/../templates/common.php');
  require_once (__DIR__ . '/../templates/trainer.php');
  require_once (__DIR__ . '/../templates/classes.php');

  $trainerId = $_GET['id'];

  $db = getDatabaseConnection();

  $trainer = Trainer::getTrainerById($db, intval($trainerId));

  drawHeader($session, 'Trainer Name', ['trainer.css']);

  drawTrainerPage($trainer);
       
  drawFooter(); 
?>
