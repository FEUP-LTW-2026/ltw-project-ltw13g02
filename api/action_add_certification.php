<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/trainer.class.php');
require_once(__DIR__ . '/../utils/helpers.php');

header('Content-Type: application/json');

$session = new Session();

require_once(__DIR__ . '/../utils/role_change_guard.php');

if (!$session->isLoggedIn()) {
    echo json_encode(['messageType' => 'error', 'message' => 'Not logged in.']);
    exit;
}

if (!$session->isTrainer()) {
    echo json_encode(['messageType' => 'error', 'message' => 'Unauthorized action!.']);
    exit;
}

if (empty($_POST['certification_id'])) {
    echo json_encode(['messageType' => 'error', 'message' => 'No certification selected.']);
    exit;
}

if(!$session->verifyCsrfToken($_POST['csrf_token'])) {
    echo json_encode(['messageType' => 'error', 'message' => 'Invalid CSRF token.']);
    exit;
}

$certificationId = (int)$_POST['certification_id'];
if ($certificationId <= 0) {
    echo json_encode(['messageType' => 'error', 'message' => 'Invalid certification ID.']);
    exit;
}

$db = getDatabaseConnection();

try {
    Trainer::addCertification($db, $session->getId(), $certificationId);
    $stmt = $db->prepare('SELECT * FROM Certification WHERE id = ?');
    $stmt->execute([$certificationId]);
    $certification = $stmt->fetch();
    echo json_encode(['messageType' => 'success', 'message' => 'Successfully added certification', 'certification' => $certification]);
} catch (PDOException $e) {
    echo json_encode(['messageType' => 'error', 'message' => 'Failed to add certification.']);
}

