<?php 

  declare(strict_types=1);

  require_once(__DIR__ . '/../../utils/session.php');
  require_once(__DIR__ . '/../../database/connection.db.php');
  require_once(__DIR__ . '/../../database/equipment_status_manager.class.php');
  require_once(__DIR__ . '/../../utils/helpers.php');

  $session = new Session();
  require_once(__DIR__ . '/../../utils/protected_file_guard.php');

  if (!$session->isLoggedIn()) {
    redirectToLogin();
  }

  if (!$session->isAdmin()) {
    redirectToHomePage();
  }

  $equipmentId = (int)($_POST['equipment_id'] ?? 0);
  $oldStatus = trim((string)($_POST['old_status'] ?? ''));
  $newStatus = trim((string)($_POST['new_status'] ?? ''));

  if ($equipmentId <= 0) {
    $session->addErrorMessage('Invalid equipment ID.');
    redirectToPreviousPage();
    exit;
  }

  if(!$session->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    $session->addErrorMessage('Invalid CSRF token.');
    redirectToPreviousPage();
  }

  try {
    $db = getDatabaseConnection();
    EquipmentStatusManager::changeStatus($db, $equipmentId, $oldStatus, $newStatus, $session);
    $session->addSuccessMessage('Equipment status updated successfully.');
  } catch (PDOException $e) {
    $session->addErrorMessage('Database error occurred while updating the equipment status.');
  }

  redirectToPreviousPage();
  exit;
?>