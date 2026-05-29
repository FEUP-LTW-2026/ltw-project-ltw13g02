<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/equipment.class.php');
  require_once(__DIR__ . '/../database/equipment_booking.class.php');

  $session = new Session();
  require_once(__DIR__ . '/../utils/protected_file_guard.php');

  require_once(__DIR__ . '/../utils/helpers.php');
  if (!$session->isLoggedIn()) {
    redirectToLogin();
  }

  if(!$session->isAdmin()) {
    $session->addErrorMessage('Only admins can delete equipment.');
    redirectToHomePage();
  }

  if (!$session->verifyCSRFToken($_POST['csrf_token'])) {
    $session->addErrorMessage('Invalid CSRF token.');
    redirectToPreviousPage();
  }

  $equipmentId = (int)$_POST['equipment_id'];

  try {
    $db = getDatabaseConnection();
    $remainingBookings = EquipmentBooking::getRemainingBookings($db, $equipmentId);

    if ($remainingBookings > 0) {
      $session->addErrorMessage('This equipment cannot be deleted because there are still future bookings for it.');
      redirectToPreviousPage();
      exit;
    }

    $stmt = $db->prepare('DELETE FROM Equipment WHERE id = ?');
    $stmt->execute([$equipmentId]);

    $session->addSuccessMessage('Equipment deleted successfully.');
  } catch (PDOException $e) {
    $session->addErrorMessage($e->getMessage());
    $session->addErrorMessage('Database error occurred while deleting the equipment.');
  }

  redirectToPreviousPage();
  exit;
?>