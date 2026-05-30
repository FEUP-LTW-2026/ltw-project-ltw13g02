<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../../utils/session.php');
  require_once(__DIR__ . '/../../database/connection.db.php');
  require_once(__DIR__ . '/../../database/equipment_type.class.php');

  $session = new Session();
  require_once(__DIR__ . '/../../utils/protected_file_guard.php');

  require_once(__DIR__ . '/../../utils/helpers.php');

  if (!$session->isLoggedIn()){
    redirectToLogin();
  }

  if (!$session->isAdmin()) {
    $session->addErrorMessage('Only admins can create equipment types.');
    redirectToHomePage();
  }

  if (!$session->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    $session->addErrorMessage('Invalid CSRF token.');
    redirectToPreviousPage();
  }

  $name = trim((string)($_POST['name'] ?? ''));

  if (empty($name)) {
    $session->addErrorMessage('Equipment type name is required.');
    redirectToPreviousPage();
    exit;
  }

  if (!validateTextBox($name)) {
    $session->addErrorMessage('Equipment type name can only contain letters, numbers, and spaces.');
    redirectToPreviousPage();
    exit;
  }

  try {
    $db = getDatabaseConnection();
    EquipmentType::create($db, $name);
    $session->addSuccessMessage('Equipment type created successfully.');
  } catch (PDOException $e) {
    if(str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
      $session->addErrorMessage('An equipment type with this name already exists.');
    } else {
      $session->addErrorMessage('Database error occurred while creating the equipment type.');
    }
  }

  redirectToPreviousPage();
  exit;
?>