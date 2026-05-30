<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../../database/connection.db.php');
  require_once(__DIR__ . '/../../database/equipment_booking.class.php');
  require_once(__DIR__ . '/../../utils/helpers.php');
  require_once(__DIR__ . '/../../utils/session.php');

  $db = getDatabaseConnection();
  
  $session = new Session();
  require_once(__DIR__ . '/../../utils/protected_file_guard.php');

  if (!$session->isLoggedIn()){
    redirectToLogin();
  }

  if (!$session->isMember()) {
    $session->addErrorMessage('Only members can reserve equipment.');
    redirectToHomePage();
  }

  if (!$session->verifyCSRFToken($_POST['csrf_token'])) {
    $session->addErrorMessage('Invalid CSRF token.');
    redirectToPreviousPage();
  }

  $equipmentBookingId = (int)$_POST['booking_id'];

  try {
    EquipmentBooking::cancel($db, $equipmentBookingId);
    $session->addSuccessMessage('Equipment reservation cancelled successfully!');
  } catch (PDOException $e) {
    $session->addErrorMessage('Failed to cancel the equipment reservation.');
  }

  redirectToPreviousPage();
?>