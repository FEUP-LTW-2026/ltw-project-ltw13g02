<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/equipment_booking.class.php');
  require_once(__DIR__ . '/../database/timeslot.class.php');
  require_once(__DIR__ . '/../utils/helpers.php');
  require_once(__DIR__ . '/../utils/session.php');

  $db = getDatabaseConnection();
  
  $session = new Session();
  require_once(__DIR__ . '/../utils/protected_file_guard.php');

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

  $memberId = (int)$session->getId();
  $equipmentId = (int)$_POST['equipment_id'];
  $bookingDate = $_POST['booking_date'];
  $timeslotId = (int)$_POST['time-slot'];

  // Check if the timeslot is still available before creating the booking
  if (!Timeslot::isAvailable($db, $timeslotId, $equipmentId, $bookingDate)) {
    $session->addErrorMessage('Sorry, this timeslot has just been booked by someone else. Please choose another timeslot.');
    redirectToPreviousPage();
  }

  try {
    EquipmentBooking::create($db, $memberId, $equipmentId, $timeslotId, $bookingDate);
    $session->addSuccessMessage('Equipment reserved successfully!');
  } catch (PDOException $e) {
    switch ($e->getMessage()){
      case str_contains($e->getMessage(), 'Cannot book multiple equipments for the same timeslot'):
        $session->addErrorMessage('Cannot book multiple equipments for the same timeslot.');
        break;
      case str_contains($e->getMessage(), 'Cannot book equipment that is not available'):
        $session->addErrorMessage('The selected equipment is not available.');
        break;
      default:
        $session->addErrorMessage('Failed to reserve the desired equipment.');
    }
  }

  // On success redirect back to the previous page
  redirectToPreviousPage();

?>