<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/gymClass.class.php');
    require_once(__DIR__ . '/../database/enrollment.class.php');

    header('Content-Type: application/json');

    $session = new Session();

    require_once(__DIR__ . '/../utils/protected_file_guard.php');

    if (!$session->isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Not logged in.']);
        exit;
    }

    if(empty($_POST['class_id'])) {
        echo json_encode(['success' => false, 'message' => 'No class selected.']);
        exit;
    }

    if(!$session->verifyCsrfToken($_POST['csrf_token'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token.']);
        exit;
    }

    $classId = (int)$_POST['class_id'];
    if ($classId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid class ID.']);
        exit;
    }

    $memberId = $session->getId();
    $db = getDatabaseConnection();

    try {
        Enrollment::addToWaitlist($db, $memberId, $classId);
        $updatedClass = GymClass::getClassById($db, $classId);
        echo json_encode([
            'messageType' => 'success',
            'message' => 'Successfully joined the waitlist.',
            'status' => 'waitlisted',
            'current_enrollment' => $updatedClass->currentEnrollment,
            'capacity' => $updatedClass->capacity,
        ]);
    } catch (PDOException $e) {
        echo json_encode(['messageType' => 'error', 'message' => 'Failed to join waitlist.']);
    }

exit;
