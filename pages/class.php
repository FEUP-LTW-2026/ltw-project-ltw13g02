<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../utils/session.php');

  $session = new Session();

  require_once(__DIR__ . '/../templates/common.php');
  require_once(__DIR__ . '/../templates/class.php');
  require_once(__DIR__ . '/../database/classType.class.php');
  require_once(__DIR__ . '/../database/gymClass.class.php');
  require_once(__DIR__ . '/../database/enrollment.class.php');
  require_once(__DIR__ . '/../database/review.class.php');

  $db = getDatabaseConnection();

  $generalClass = ClassType::getClassTypeById($db, intval($_GET['id']));
  $classes = GymClass::getClassesByTypeId($db, intval($_GET['id']));
  
 if ($session->isLoggedIn()) {
    $memberId = $session->getId();
    $enrolledClasses = Enrollment::getEnrolledClasses($db, $session->getId());
    $waitlistedClasses = Enrollment::getWaitlistedClasses($db, $session->getId());
  } else {
    $enrolledClasses = [];
    $waitlistedClasses = [];
  }

  $reviews = Review::getReviewsByClassType($db, intval($_GET['id']));
  $classTypeRating = Review::getRatingAndCountByClassType($db, intval($_GET['id']));

  drawHeader($session, $generalClass->name, ['class.css', 'classes.css']);

  drawClassPage($session, $generalClass, $classes, $enrolledClasses, $waitlistedClasses, $reviews, $classTypeRating);

  drawFooter();
?>