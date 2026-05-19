<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    function redirectToPreviousPage() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $classId = $_POST['class_id'];
    $date = $_POST['date'];

    // Verify if the form fields are empty
    if (empty($classId) || empty($date)) {
        $session->addMessage('error', 'All fields are mandatory.');
        redirectToPreviousPage();
    }

    require_once(__DIR__ . '/../database/class.class.php');
    require_once(__DIR__ . '/../database/enrollment.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    $db = getDatabaseConnection();
    $class = GymClass::getClassById($db, (int)$classId);
    
    // Verify if the class exists
    if ($class === null) {
        $session->addMessage('error', 'Class not found.');
        redirectToPreviousPage();
    }

    $weekday = date('N', strtotime($date));

    // Verify if the class is on the selected date
    if ($class->weekday !== GymClass::$weekdays[(int)$weekday]) {
        $session->addMessage('error', 'Class is not offered on the selected date.');
        redirectToPreviousPage();
    }

    // Verify if the class is already full
    if (count($class->enrolled) >= $class->capacity) {
        $session->addMessage('error', 'Class is already full. Added to waitlist.');
        Enrollment::create($db, null, (int)$classId, 'waitlisted');
        redirectToPreviousPage();
    }

    $memberId = $session->getId();

    // Verify if the user is already enrolled in the class
    if (in_array($memberId, $class->enrolled)) {
        $session->addMessage('error', 'You are already enrolled in this class.');
        redirectToPreviousPage();
    }

    Enrollment::create($db, (int)$memberId, (int)$classId, 'enrolled');

    $session->addMessage('success', 'You have been enrolled in the class.');
    redirectToPreviousPage();
?>