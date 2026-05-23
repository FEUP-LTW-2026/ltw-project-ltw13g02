<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');

  $session = new Session();
  require_once(__DIR__ . '/../utils/role_change_guard.php');

  if(!$session->isLoggedIn() || !$session->isAdmin()) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }

  if (empty($_POST['tier_id']) || empty($_POST['name']) || empty($_POST['price']) || !isset($_POST['description']) || empty($_POST['booking_window_days'])) {
    $session->addErrorMessage('Invalid request. Please fill in all required fields.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }

  $db = getDatabaseConnection();
  $tierId = (int) $_POST['tier_id'];
  $name = trim($_POST['name']);
  $price = (float) $_POST['price'];
  $description = trim($_POST['description']);
  $bookingWindowDays = (int) $_POST['booking_window_days'];
  
  try {
    $stmt = $db->prepare('UPDATE MembershipTier SET name = ?, description = ?, monthly_fee = ?, booking_window_days = ? WHERE id = ?');
    $stmt->execute([$name, $description, $price, $bookingWindowDays, $tierId]);

    $session->addSuccessMessage('Membership tier updated successfully.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  } catch (PDOException $e) {
    $session->addErrorMessage('Database error occurred while updating the tier.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
?>