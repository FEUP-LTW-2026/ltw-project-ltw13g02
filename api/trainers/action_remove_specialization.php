<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../utils/session.php');
require_once(__DIR__ . '/../../database/connection.db.php');
require_once(__DIR__ . '/../../database/trainer.class.php');
require_once(__DIR__ . '/../../utils/helpers.php');

header("Content-type: application/json");

$session = new Session();

require_once(__DIR__ . '/../../utils/protected_file_guard.php');

if (!$session->isLoggedIn()) {
    echo json_encode(['messageType' => 'error', 'message' => 'Not logged in.']);
    exit;
}

if (!$session->isTrainer()) {
    echo json_encode(['messageType' => 'error', 'message' => 'Unauthorized action!.']);
    exit;
}

if(empty($_POST['specialization_id'])) {
    echo json_encode(['messageType' => 'error', 'message' => 'No specialization selected.']);
    exit;
}

if (!$session->verifyCSRFToken($_POST['csrf_token'])) {
    echo json_encode(['messageType' => 'error', 'message' => 'Invalid CSRF token.']);
    exit;
}

$specializationId = (int) $_POST['specialization_id'];
if ($specializationId <= 0) {
    echo json_encode(['messageType' => 'error', 'message' => 'Invalid specialization ID.']);
    exit;
}

$db = getDatabaseConnection();

try {
    Trainer::removeSpecialization($db, $session->getId(), $specializationId);
    echo json_encode(['messageType' => 'success', 'message' => 'Removed specialization.']);
} catch (PDOException $e) {
    echo json_encode(['messageType' => 'error', 'message' => 'Unable to remove specialization.']);
}
