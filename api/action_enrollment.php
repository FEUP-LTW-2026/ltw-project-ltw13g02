<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/gymClass.class.php');
require_once(__DIR__ . '/../database/classType.class.php');
require_once(__DIR__ . '/../database/enrollment.class.php');

header('Content-Type: application/json');

$session = new Session();

require_once(__DIR__ . '/../utils/protected_file_guard.php');

if (!$session->isLoggedIn()) {
    echo json_encode(['messageType' => 'error', 'message' => 'Not logged in.']);
    exit;
}

if(empty($_POST['class_id'])) {
    echo json_encode(['messageType' => 'error', 'message' => 'No class selected.']);
    exit;
}

if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
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

$classType = ClassType::getClassTypeById($db, $class->classTypeId);

if (Enrollment::isEnrolled($db, $memberId, $classId)) {
    echo json_encode(['messageType' => 'error', 'message' => 'Already enrolled.']);
    exit;
}

if ($class->currentEnrollment >= $classType->capacity) {
    try {
        Enrollment::create($db, $memberId, $classId, 'waitlisted');
        $updatedClass = GymClass::getClassById($db, $classId);
        echo json_encode([
            'messageType' => 'success',
            'message' => 'Class is full. Added to waitlist.',
            'status' => 'waitlisted',
            'current_enrollment' => $updatedClass->currentEnrollment,
            'capacity' => $updatedClass->capacity
        ]);
    } catch (PDOException $e) {
        echo json_encode(['messageType' => 'error', 'message' => 'Failed to add to waitlist.']);
    }
    exit;
}

try {
    Enrollment::create($db, $memberId, $classId, 'enrolled');
    $updatedClass = GymClass::getClassById($db, $classId);
    echo json_encode([
        'messageType' => 'success',
        'message' => 'Successfully enrolled!',
        'status' => 'enrolled',
        'current_enrollment' => $updatedClass->currentEnrollment,
        'capacity' => $updatedClass->capacity
    ]);
} catch (PDOException $e) {
    echo json_encode(['messageType' => 'error', 'message' => 'Failed to enroll.']);
}

exit;
