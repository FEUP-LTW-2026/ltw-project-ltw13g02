<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/class.class.php');

  require_once(__DIR__ . '/../templates/common.php');
  require_once(__DIR__ . '/../templates/class.php');

  $db = getDatabeseConnection();

  $class = GymClass::getClassById($db, intval($_GET['id']));

  // Dummy data for classss
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

  drawHeader($class->name, ['class.css', 'classes.css']);

  drawClassPage($class, $reviews);

  drawFooter();
?>