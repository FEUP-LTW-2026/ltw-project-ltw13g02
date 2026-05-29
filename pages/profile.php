<?php
    declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/authenticated_user.class.php');
  require_once(__DIR__ . '/../database/enrollment.class.php');
  require_once(__DIR__ . '/../database/membership.class.php');
  require_once(__DIR__ . '/../database/equipment_booking.class.php');
  require_once(__DIR__ . '/../templates/common.php');
  require_once(__DIR__ . '/../templates/profile.php');
  require_once(__DIR__ . '/../templates/classes.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/protected_file_guard.php');

    if (!$session->isLoggedIn()) {
        header('Location: login.php');
        exit;
    }

    $db = getDatabaseConnection();
    $user = AuthenticatedUser::getUser($db, $session->getId());

    if (!$user) {
        $session->logout();
        $session->addErrorMessage('User not found. Please log in again.');
        header('Location: login.php');
        exit;
    }

    $classes = Enrollment::getEnrolledClasses($db, $session->getId());
    $waitlistedClasses = Enrollment::getWaitlistedClasses($db, $session->getId());
    $equipmentBooked = EquipmentBooking::getUserEquipmentBookings($db, $session->getId());
    $numMemberships = Membership::getNumMemberships($db, $session->getId());

    drawHeader($session, 'Profile', ['profile.css']);
    drawProfilePage($session->getId(), $user->getFullName(), $user->getUsername(), $classes, $waitlistedClasses, $equipmentBooked, $numMemberships, $session);
    drawFooter();
?>

<script src="../javascript/enrollment.js" defer></script>
<script src="../javascript/membershipsPaging.js" defer></script>