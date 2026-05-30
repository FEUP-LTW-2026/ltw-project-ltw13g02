<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../../utils/session.php');
  require_once(__DIR__ . '/../../database/connection.db.php');
  require_once(__DIR__ . '/../../database/timeslot.class.php');

  $session = new Session();
  require_once(__DIR__ . '/../../utils/protected_file_guard.php');

  require_once(__DIR__ . '/../../utils/helpers.php');
  if (!$session->isLoggedIn()) {
    redirectToLogin();
  }
  
  if (!$session->isAdmin()) {
    $session->addErrorMessage('Only admins can create new timeslots.');
    redirectToHomePage();
  }

  if (!$session->verifyCSRFToken($_POST['csrf_token'])) {
    $session->addErrorMessage('Invalid CSRF token.');
    redirectToPreviousPage();
  }

  $startTime = trim((string)($_POST['start_time'] ?? ''));
  $endTime = trim((string)($_POST['end_time'] ?? ''));

  if ($startTime === '' || $endTime === '') {
    $session->addErrorMessage('Start time and end time are required.');
    redirectToPreviousPage();
  }

  try {
    $db = getDatabaseConnection();
    Timeslot::create($db, $startTime, $endTime);
    $session->addSuccessMessage('Timeslot created successfully.');
  } catch (PDOException $e) {
    if(str_contains($e->getMessage(), 'Cannot create overlapping timeslots.')) {
      $session->addErrorMessage('This timeslot overlaps with an existing timeslot. Please choose a different time.');
    } else {
      $session->addErrorMessage($e->getMessage());
      $session->addErrorMessage('Database error occurred while creating the timeslot.');
    }
  }

  redirectToPreviousPage();
  exit;
?>