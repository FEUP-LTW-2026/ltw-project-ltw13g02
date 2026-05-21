<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../utils/session.php');

  $session = new Session();

  require_once(__DIR__ . '/../templates/common.php');
  require_once(__DIR__ . '/../templates/class.php');
  require_once(__DIR__ . '/../database/classType.class.php');
  require_once(__DIR__ . '/../database/gymClass.class.php');

  $db = getDatabaseConnection();

  $generalClass = ClassType::getClassTypeById($db, intval($_GET['id']));
  $classes = GymClass::getClassesByTypeId($db, intval($_GET['id']));
  $enrolledClasses = GymClass::getEnrolledClasses($db, $session->getId());

  // Dummy data for class reviews.
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

  drawHeader($session, $generalClass->name, ['class.css', 'classes.css']);

  drawClassPage($session, $generalClass, $classes, $enrolledClasses, $reviews);

  drawFooter();
?>