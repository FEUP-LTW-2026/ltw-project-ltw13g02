<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/gymClass.class.php');
    require_once(__DIR__ . '/../database/classType.class.php');
    require_once(__DIR__ . '/../database/enrollment.class.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/role_change_guard.php');

    if (!$session->isLoggedIn()) {
        redirectToLogin();
    }

    if(empty($_POST['class_id'])) {
        $session->addErrorMessage('Class ID is required.');
        redirectToPreviousPage();
    }

    if(!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token.');
        redirectToPreviousPage();
    }

    // Validate input
    $classId = (int)$_POST['class_id'];;
    if ($classId <= 0) {
        $session->addErrorMessage('Invalid class ID.');
        redirectToPreviousPage();
    }

    $memberId = $session->getId();
    $db = getDatabaseConnection();

    // Fetch class and its type
    $class = GymClass::getClassById($db, $classId);
    if ($class === null) {
        $session->addErrorMessage('Class not found.');
        redirectToPreviousPage();
    }

    $classType = ClassType::getClassTypeById($db, $class->classTypeId);
    if ($classType === null) {
        $session->addErrorMessage('Class type not found.');
        redirectToPreviousPage();
    }

    // Check if already enrolled; TODO: already checked in the template file.
    if (Enrollment::isEnrolled($db, $memberId, $classId)) {
        $session->addErrorMessage('You are already enrolled in this class.');
        redirectToPreviousPage();
    }

    // Check if class is full
    if ($class->currentEnrollment >= $classType->capacity) {
        $session->addErrorMessage('Class is full. Added to waitlist.');
        try {
            Enrollment::create($db, $memberId, $classId, 'waitlisted');
            $session->addSuccessMessage('Added to waitlist.');
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $session->addErrorMessage('Failed to add to waitlist.');
        }
        redirectToPreviousPage();
    }

    // Enroll member
    try {
        Enrollment::create($db, $memberId, $classId, 'enrolled');
        $session->addSuccessMessage('Successfully enrolled in the class!');
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $session->addErrorMessage('Failed to enroll: ' . $e->getMessage());
    }

    redirectToPreviousPage();
?>