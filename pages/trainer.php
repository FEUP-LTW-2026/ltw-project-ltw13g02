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
  // Dummy data for trainer
  $bio = 'Hi, I&#39;m Alex Rivera, a certified personal trainer with over 8 years of experience helping clients reach their fitness goals. I specialize in strength training and functional fitness, focusing on building practical, sustainable habits that lead to long-term success.';

  drawHeader($session, 'Trainer Name', ['trainer.css']);

  drawTrainerPage($trainer, $bio);
       
  drawFooter(); 
?>
