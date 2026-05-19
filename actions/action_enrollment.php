<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit;
    }

    function redirectToPreviousPage() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $classId = $_POST['class_id'];
    $date = $_POST['date'];

    // Verify if the form fields are empty
    if (empty($classId) || empty($date)) {
        $session->addErrorMessage('All fields are mandatory.');
        redirectToPreviousPage();
    }

    require_once(__DIR__ . '/../database/class.class.php');
    require_once(__DIR__ . '/../database/enrollment.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    $db = getDatabaseConnection();
    $class = GymClass::getClassById($db, (int)$classId);
    
    // Verify if the class exists
    if ($class === null) {
        $session->addErrorMessage('Class not found.');
        redirectToPreviousPage();
    }

    $weekday = date('N', strtotime($date));

    // Verify if the class is on the selected date
    if ($class->weekday !== GymClass::$weekdays[(int)$weekday]) {
        $session->addErrorMessage('Class is not offered on the selected date.');
        redirectToPreviousPage();
    }

    // Verify if the class is already full
    if (count($class->enrolled) >= $class->capacity) {
        $session->addErrorMessage('Class is already full. Added to waitlist.');
        Enrollment::create($db, null, (int)$classId, 'waitlisted');
        redirectToPreviousPage();
    }

    $memberId = $session->getId();

    // Verify if the user is already enrolled in the class
    if (in_array($memberId, $class->enrolled)) {
        $session->addErrorMessage('You are already enrolled in this class.');
        redirectToPreviousPage();
    }

    try {
        Enrollment::create($db, (int)$memberId, (int)$classId, 'enrolled');
        $session->addSuccessMessage('You have been enrolled in the class.');
        redirectToPreviousPage();
    } catch (PDOException $e) {
        $session->addErrorMessage('Database error occurred.');
        redirectToPreviousPage();
    }

?>