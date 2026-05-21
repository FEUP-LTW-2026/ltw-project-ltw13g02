<?php
    declare(strict_types=1);

    // TODO: require_once in the beggining of the file or in the middle?
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/gymClass.class.php');
    require_once(__DIR__ . '/../database/classType.class.php');
    require_once(__DIR__ . '/../database/enrollment.class.php');

    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit;
    }

    // TODO: this function is needed in almost every action file (create a helper function?)
    function redirectToPreviousPage() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Validate input
    $classId = $_POST['class_id'] ?? null;
    if (empty($classId)) {
        $session->addErrorMessage('Class ID is required.');
        redirectToPreviousPage();
    }

    $classId = (int)$classId;
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
    if (Enrollment::isAlreadyEnrolled($db, $memberId, $classId)) {
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