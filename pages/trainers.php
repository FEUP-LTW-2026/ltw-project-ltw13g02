<?php
	declare(strict_types = 1);

	require_once (__DIR__ . '/../templates/common.php');
	require_once (__DIR__ . '/../templates/trainers.php');

  // Dummy data for trainers
  $trainers = [
    ['name' => 'Alex Rivera', 'specialty' => 'Strength Training, Weight Loss, Injury Rehab'],
    ['name' => 'Jamie Lee', 'specialty' => 'Yoga, Pilates, Mind-Body Wellness'],
    ['name' => 'Taylor Morgan', 'specialty' => 'Cardio, HIIT, Endurance Training'],
    ['name' => 'Jordan Kim', 'specialty' => 'CrossFit, Functional Fitness, Group Classes']
  ];

	drawHeader('Trainers', ['trainers.css','classes.css']);
  
	drawIntro('REAL EXPERTS. REAL RESULTS', 'Meet our <b>Trainers</b>', 'Meet our certified coaches and find the right trainer for your goals.');

  drawTrainersPage($trainers);

  drawFooter();
?>