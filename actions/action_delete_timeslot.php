<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');

  $session = new Session();
  require_once(__DIR__ . '/../utils/protected_file_guard.php');

  require_once(__DIR__ . '/../utils/helpers.php');
  if (!$session->isLoggedIn()) {
    redirectToLogin();
  }

  if(!$session->isAdmin()) {
    $session->addErrorMessage('Only admins can delete timeslots.');
    redirectToHomePage();
  }

  if (!$session->verifyCSRFToken($_POST['csrf_token'])) {
    $session->addErrorMessage('Invalid CSRF token.');
    redirectToPreviousPage();
  }

  $timeslotId = (int)$_POST['timeslot_id'];

  try {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT COUNT(*) FROM EquipmentBooking WHERE timeslot_id = ?');
    $stmt->execute([$timeslotId]);

    if ((int)$stmt->fetchColumn() > 0) {
      $session->addErrorMessage('This timeslot cannot be deleted because bookings still use it.');
      redirectToPreviousPage();
      exit;
    }

    $stmt = $db->prepare('DELETE FROM Timeslot WHERE id = ?');
    $stmt->execute([$timeslotId]);

    $session->addSuccessMessage('Timeslot deleted successfully.');
  } catch (PDOException $e) {
    $session->addErrorMessage('Database error occurred while deleting the timeslot.');
  }

  redirectToPreviousPage();
  exit;
?>