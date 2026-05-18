<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) {
    header('Location: login.php');

  } else {
    require_once(__DIR__ . '/../templates/common.php');
    require_once(__DIR__ . '/../templates/profile.php');
    require_once(__DIR__ . '/../templates/classes.php');

    // Dummy data for user profile
    $name = 'John Doe';
    $username = 'JohnnyD_123';
    $classes = [
      ['name' => 'Morning Yoga', 'schedule' => 'Monday, Wednesday, Friday', 'trainer' => 'Alex Rivera'],
      ['name' => 'Cardio Blast', 'schedule' => 'Tuesday, Thursday', 'trainer' => 'Alex Rivera'],
      ['name' => 'Weightlifting', 'schedule' => 'Monday, Wednesday', 'trainer' => 'John Doe']
    ];

    drawHeader($session, 'Profile', ['profile.css']);

    drawProfilePage($name, $username, $classes);

    drawFooter();
  }
?>
