<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/membership_tier.class.php');

  $session = new Session();

  if (!$session->isLoggedIn() || !$session->isAdmin()) {
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php'));
    exit;
  }

  if (empty($_POST['name']) || empty($_POST['price']) || empty($_POST['booking_window_days'])) {
    $session->addErrorMessage('Invalid request. Please fill in all required fields.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
    exit;
  }

  if (($_POST['csrf_token'] ?? '') !== $session->getCSRFToken()) {
    $session->addErrorMessage('Invalid CSRF token. Please try again.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
    exit;
  }

  $name = trim((string)$_POST['name']);
  $price = (float)$_POST['price'];
  $bookingWindowDays = (int)$_POST['booking_window_days'];

  $description = '';
  if (isset($_POST['description'])) {
    if (is_array($_POST['description'])) {
      $parts = [];
      foreach ($_POST['description'] as $part) {
        $trimmedPart = trim((string)$part);
        if ($trimmedPart !== '') {
          $parts[] = $trimmedPart;
        }
      }
      $description = implode(';', $parts);
    } else {
      $description = trim((string)$_POST['description']);
    }
  }

  try {
    $db = getDatabaseConnection();
    MembershipTier::create($db, $name, $description, $price, $bookingWindowDays);

    $session->addSuccessMessage('Membership tier created successfully.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
    exit;
  } catch (PDOException $e) {
    $session->addErrorMessage('Database error occurred while creating the tier.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
    exit;
  } catch (Exception $e) {
    $session->addErrorMessage('An unexpected error occurred while creating the tier.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
    exit;
  }
?>