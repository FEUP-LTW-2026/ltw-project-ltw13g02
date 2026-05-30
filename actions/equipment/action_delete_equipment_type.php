<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../../utils/session.php');
  require_once(__DIR__ . '/../../database/connection.db.php');
  require_once(__DIR__ . '/../../database/equipment_type.class.php');

  $session = new Session();
  require_once(__DIR__ . '/../../utils/protected_file_guard.php');

  require_once(__DIR__ . '/../../utils/helpers.php');
  if (!$session->isLoggedIn()) {
    redirectToLogin();
  }

  if(!$session->isAdmin()) {
    $session->addErrorMessage('Only admins can delete equipment types.');
    redirectToHomePage();
  }

  if (!$session->verifyCSRFToken($_POST['csrf_token'])) {
    $session->addErrorMessage('Invalid CSRF token.');
    redirectToPreviousPage();
  }

  $equipmentTypeId = (int)$_POST['equipment_type_id'];

  try {
    $db = getDatabaseConnection();

    $stmt = $db->prepare('DELETE FROM EquipmentType WHERE id = ?');
    $stmt->execute([$equipmentTypeId]);

    $session->addSuccessMessage('Equipment type deleted successfully.');
  } catch (PDOException $e) {
    $session->addErrorMessage('Database error occurred while deleting the equipment type.');
  }

  redirectToPreviousPage();
  exit;
?>