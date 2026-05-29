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
  $allSpecializations = Trainer::getAllSpecializations($db);
  $allCertifications = Trainer::getAllCertifications($db);

  $nextClassesToSchedule = [];
  if ($session->isTrainer()) {
    $nextClassesToSchedule = Trainer::getNextClassesToSchedule($db, $trainer->getId());
  }

  drawHeader($session, 'Trainer Name', ['trainer.css']);

  drawTrainerPage($trainer, $allSpecializations, $allCertifications, $nextClassesToSchedule, $session);
       
  drawFooter(); 
?>

<script src="../javascript/trainerInfo.js" defer></script>
<script src="../javascript/enrolledMembers.js" defer></script>
