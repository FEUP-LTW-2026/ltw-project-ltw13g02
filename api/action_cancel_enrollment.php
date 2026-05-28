<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/gymClass.class.php');
    require_once(__DIR__ . '/../database/enrollment.class.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    header('Content-Type: application/json');

    $session = new Session();

    require_once(__DIR__ . '/../utils/role_change_guard.php');

    if (!$session->isLoggedIn()) {
        echo json_encode(['messageType' => 'error', 'message' => 'Not logged in.']);
        exit;
    }

    if(empty($_POST['class_id'])) {
        echo json_encode(['messageType' => 'error', 'message' => 'No class selected.']);
        exit;
    }

    if (!$session->verifyCSRFToken($_POST['csrf_token'])) {
        echo json_encode(['messageType' => 'error', 'message' => 'Invalid CSRF token.']);
        exit;
    }

    $classId = (int)$_POST['class_id'];
    if ($classId <= 0) {
        echo json_encode(['messageType' => 'error', 'message' => 'Invalid class ID.']);
        exit;
    }

    $memberId = $session->getId();
    $db = getDatabaseConnection();

    $class = GymClass::getClassById($db, $classId);
    if ($class === null) {
        echo json_encode(['messageType' => 'error', 'message' => 'Class not found.']);
        exit;
    }

    try {
        Enrollment::cancelEnrollment($db, $memberId, $classId);
        $updatedClass = GymClass::getClassById($db, $classId);
        echo json_encode([
            'messageType' => 'success',
            'message' => 'Enrollment cancelled.',
            'status' => 'cancelled',
            'current_enrollment' => $updatedClass->currentEnrollment,
            'capacity' => $updatedClass->capacity
        ]);
    } catch (PDOException $e) {
        echo json_encode(['messageType' => 'error', 'message' => 'Failed to cancel enrollment.']);
    }

    exit;
