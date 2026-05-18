<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once (__DIR__ . '/../templates/common.php');
  require_once (__DIR__ . '/../templates/classes.php');

  // Dummy data for classes
  $classes = [
    ['name' => 'Morning Yoga', 'schedule' => 'Monday, Wednesday, Friday - 7:00 PM to 8:00 PM', 'trainer' => 'Alex Rivera'],
    ['name' => 'Cardio Blast', 'schedule' => 'Tuesday, Thursday - 6:00 PM to 7:00 PM', 'trainer' => 'Alex Rivera'],
    ['name' => 'Weightlifting', 'schedule' => 'Monday, Wednesday - 5:00 PM to 6:00 PM', 'trainer' => 'John Doe'],
    ['name' => 'Pilates', 'schedule' => 'Tuesday, Thursday - 7:00 PM to 8:00 PM', 'trainer' => 'Emily Jones'],
    ['name' => 'CrossFit', 'schedule' => 'Friday, Saturday - 10:00 AM to 11:00 AM', 'trainer' => 'John Doe'],
    ['name' => 'Boxing', 'schedule' => 'Wednesday, Friday - 6:00 PM to 7:00 PM', 'trainer' => 'Michael Brown']
  ];

  drawHeader($session, 'Classes', ['classes.css']);

  drawIntro('WHAT WE OFFER', 'Our <b>classes</b>', '40+ weekly sessions across strength, cardio, flexibility and recovery. Something for every level.');

  drawClassesPage($classes);
  
  drawFooter(); 
?>
