<?php
	declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');

  $session = new Session();

	require_once (__DIR__ . '/../templates/common.php');
	require_once (__DIR__ . '/../templates/trainers.php');
  require_once (__DIR__ . '/../database/trainer.class.php');
  require_once (__DIR__ . '/../database/connection.db.php');

  $db = getDatabaseConnection();

  $trainers = Trainer::getAllTrainers($db);

	drawHeader($session, 'Trainers', ['trainers.css','classes.css']);
  
	drawIntro('REAL EXPERTS. REAL RESULTS', 'Meet our <b>Trainers</b>', 'Meet our certified coaches and find the right trainer for your goals.');

  drawTrainersPage($trainers);

  drawFooter();
?>