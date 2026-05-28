<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/equipment_type.class.php');
  require_once(__DIR__ . '/../database/equipment.class.php');

  $session = new Session();
  require_once(__DIR__ . '/../utils/protected_file_guard.php');

  require_once(__DIR__ . '/../utils/helpers.php');

  if (!$session->isLoggedIn()) {
    redirectToLogin();
  }

  if (!$session->isAdmin()) {
    $session->addErrorMessage('Only admins can create new equipment.');
    redirectToHomePage();
  }

  if (!$session->verifyCSRFToken($_POST['csrf_token'])) {
    $session->addErrorMessage('Invalid CSRF token.');
    redirectToPreviousPage();
  }

  $name = trim((string)($_POST['name'] ?? ''));
  $equipmentTypeId = (int)($_POST['equipment_type_id'] ?? 0);

  if (empty($name) || $equipmentTypeId <= 0) {
    $session->addErrorMessage('Equipment name and equipment type are required.');
    redirectToPreviousPage();
    exit;
  }

  if (!validateTextBox($name)) {
    $session->addErrorMessage('Equipment name can only contain letters, numbers, and spaces.');
    redirectToPreviousPage();
    exit;
  }

  try {
    $db = getDatabaseConnection();
    Equipment::create($db, $name, 'available', $equipmentTypeId);
    $session->addSuccessMessage('Equipment created successfully.');
  } catch (PDOException $e) {
    if(str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
      $session->addErrorMessage('An equipment with this name already exists.');
    } else {
      $session->addErrorMessage($e->getMessage());
      $session->addErrorMessage('Database error occurred while creating the equipment.');
    }
  }

  redirectToPreviousPage();
  exit;
?>