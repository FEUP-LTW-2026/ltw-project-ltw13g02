<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once (__DIR__ . '/../templates/common.php');
  require_once (__DIR__ . '/../templates/trainer.php');
  require_once (__DIR__ . '/../templates/classes.php');

  // Dummy data for trainer
  $firstname = 'Alex';
  $lastname = 'Rivera';
  $bio = 'Hi, I&#39;m Alex Rivera, a certified personal trainer with over 8 years of experience helping clients reach their fitness goals. I specialize in strength training and functional fitness, focusing on building practical, sustainable habits that lead to long-term success.';
  $specialties = [
    ['name' => 'Strength Training', 'icon' => 'exercise', 'description' => 'Focused on building muscle, improving strength, and enhancing overall performance through structured and progressive training programs.'],
    ['name' => 'Weight Loss', 'icon' => 'monitor_weight', 'description' => 'Combining effective workouts with sustainable habits to help you lose weight, stay consistent, and maintain long-term results.'],
    ['name' => 'Injury Rehab & Prevention', 'icon' => 'personal_injury', 'description' => 'Designed to support recovery and reduce the risk of injury by improving mobility, stability, and proper movement patterns.']
  ];
  $certifications = ['NASM Certified Personal Trainer', 'CrossFit Level 1 Trainer'];
  $classes = [
    ['name' => 'Strength Training', 'schedule' => 'Monday, Wednesday, Friday - 7:00 PM', 'trainer' => 'Alex Rivera'],
    ['name' => 'Weight Loss Bootcamp', 'schedule' => 'Tuesday, Thursday - 6:00 PM', 'trainer' => 'Alex Rivera'],
    ['name' => 'Injury Rehab & Prevention', 'schedule' => 'Saturday - 10:00 AM', 'trainer' => 'Alex Rivera']
  ];


  drawHeader($session, 'Trainer Name', ['trainer.css']);

  drawTrainerPage($firstname, $lastname, $bio, $specialties, $certifications, $classes);
       
  drawFooter(); 
?>
