<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../templates/common.php');
  require_once(__DIR__ . '/../templates/class.php');

  // Dummy data for classss
  $className = 'Morning Yoga';
  $schedule = 'Monday, Wednesday, Friday';
  $trainer = 'Alex Rivera';
  $reviews = [
    [
      'name' => 'Maria Silva',
      'rating' => '5',
      'comment' => 'Amazing class with an amazing trainer! Alex\'s guidance and energy made the session truly special.'
    ],
    [  
      'name' => 'Joao Cunha',
      'rating' => '4',
      'comment' => 'I loved the variety of poses and the calming atmosphere. Highly recommend!'
    ]
  ];

  drawHeader($session, $className, ['class.css', 'classes.css']);

  drawClassPage($className, $schedule, $trainer, $reviews);

  drawFooter();
?>