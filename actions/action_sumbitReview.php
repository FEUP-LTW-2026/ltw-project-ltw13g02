<?php 
    declare(strict_types=1);

    require_once (__DIR__ . '../../utils/session.php');
    $session = new Session();

    // Reviews can only be submitted by logged in members
    if(!$session->isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit;
    }

    if(!$session->isMember()) {
        header('Location: ../pages/index.php#membership');
        exit;
    }

    if(!isset($_POST['comment'], $_POST['rating'], $_POST['class_type_id'])) {
        $session->addErrorMessage('All fields are required.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if ($_POST['csrf_token'] !== $session->getCSRFToken()) {
        $session->addErrorMessage('Invalid CSRF token.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $comment = trim($_POST['comment']);
    $rating = (int) $_POST['rating'];
    $class_type_id = (int) $_POST['class_type_id'];

    require_once (__DIR__ . '/../database/connection.db.php');
    $db = getDatabaseConnection();

    require_once(__DIR__ . '/../database/enrollment.class.php');
    $hasAttended = Enrollment::hasAttendedClassType($db, $session->getId(), $class_type_id);


    if(!$hasAttended) {
        $session->addErrorMessage('You can only review a class type if you have attended a class of that type.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    require_once(__DIR__ . '/../database/review.class.php');
    $hasReviewed = Review::hasReviewedClassTypeInCurrentMonth($db, $session->getId(), $class_type_id);

    if($hasReviewed) {
        $session->addErrorMessage('You can only review a class type once per month.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    try {
        Review::create($db, $session->getId(), $class_type_id, $comment, $rating);
        $session->addSuccessMessage('Review submitted successfully.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } catch (PDOException $e) {
        $session->addErrorMessage($e->getMessage());
        $session->addErrorMessage('Database error occurred while submitting the review.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

?>