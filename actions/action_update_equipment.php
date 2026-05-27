<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/equipment.class.php');

  $session = new Session();
  require_once(__DIR__ . '/../utils/role_change_guard.php');

  if(!$session->isLoggedIn()) {
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../index.php'));
    exit;
  }

  if (!$session->isAdmin()) {
    $session->addErrorMessage('You do not have permission to perform this action.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../index.php'));
    exit;
  }

  if (($_POST['csrf_token'] ?? '') !== $session->getCSRFToken()) {
    $session->addErrorMessage('Invalid CSRF token. Please try again.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#equipment-management'));
    exit;
  }

  $name = trim((string)($_POST['name'] ?? ''));
  $equipmentId = (int)($_POST['equipment_id'] ?? 0);
  $equipmentTypeId = (int)($_POST['equipment_type_id'] ?? 0);

  if (empty($name) || $equipmentTypeId <= 0) {
    $session->addErrorMessage('Equipment name and equipment type are required.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#equipment-management'));
    exit;
  }

  if (!preg_match('/^[a-zA-Z0-9 ]+$/', $name)) {
    $session->addErrorMessage('Equipment name can only contain letters, numbers, and spaces.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }

  try {
    $db = getDatabaseConnection();
    $equipment = Equipment::getEquipmentById($db, $equipmentId);
    $equipment->setName($name);
    $equipment->setEquipmentTypeId($equipmentTypeId);
    $equipment->save($db);
    $session->addSuccessMessage('Equipment updated successfully.');
  } catch (PDOException $e) {
    if(str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
      $session->addErrorMessage('An equipment with this name already exists.');
    } else {
      $session->addErrorMessage('Database error occurred while updating the equipment.');
    }
  }

  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit;
?>