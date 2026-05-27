<?php 
    declare(strict_types=1);

    require_once (__DIR__ . '/../utils/session.php');
    require_once (__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/enrollment.class.php');
    require_once(__DIR__ . '/../database/review.class.php');
    require_once (__DIR__ . '/../utils/helpers.php');

    $session = new Session();

    require_once (__DIR__ . '/../utils/role_change_guard.php');

    // Reviews can only be submitted by logged in members
    if(!$session->isLoggedIn()) {
        redirectToLogin();
    }

    if(!$session->isMember()) {
        header('Location: ../pages/index.php#membership');
        exit;
    }

    if(empty($_POST['comment']) || empty($_POST['rating']) || empty($_POST['class_type_id']) || empty($_POST['csrf_token'])) {
        $session->addErrorMessage('All fields are required.');
        redirectToPreviousPage();
    }

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token.');
        redirectToPreviousPage();
    }

    $comment = (string) $_POST['comment'];
    if (!   validateTextBox($_POST['comment'])) {
        $session->addErrorMessage('Comment cannot exceed 500 characters.');
        redirectToPreviousPage();
    }

    $rating = (int) $_POST['rating'];
    if ($rating < 1 || $rating > 5) {
        $session->addErrorMessage('Rating must be between 1 and 5.');
        redirectToPreviousPage();
    }

    $class_type_id = (int) $_POST['class_type_id'];
    if ($class_type_id <= 0) {
        $session->addErrorMessage('Invalid class type ID.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();
    $hasAttended = Enrollment::hasAttendedClassType($db, $session->getId(), $class_type_id);


    if(!$hasAttended) {
        $session->addErrorMessage('You can only review a class type if you have attended a class of that type.');
        redirectToPreviousPage();
    }

    $hasReviewed = Review::hasReviewedClassTypeInCurrentMonth($db, $session->getId(), $class_type_id);

    if($hasReviewed) {
        $session->addErrorMessage('You can only review a class type once per month.');
        redirectToPreviousPage();
    }

    try {
        Review::create($db, $session->getId(), $class_type_id, $comment, $rating);
        $session->addSuccessMessage('Review submitted successfully.');
    } catch (PDOException $e) {
        $session->addErrorMessage($e->getMessage());
        $session->addErrorMessage('Database error occurred while submitting the review.');
    }

    redirectToPreviousPage();
?>