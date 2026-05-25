<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/membership_tier.class.php');

  $session = new Session();
  require_once(__DIR__ . '/../utils/role_change_guard.php');

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

  if( !preg_match('/^[a-zA-Z]+$/', $name) ) {
    $session->addErrorMessage('Name can only contain letters.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
    exit;
  }

  $description = '';
  if (isset($_POST['description'])) {
    if (is_array($_POST['description'])) {
      $parts = [];
      foreach ($_POST['description'] as $part) {
        $trimmedPart = trim((string)$part);
        if ($trimmedPart !== '') {
          // Validate each description part: max 200 chars, no HTML tags
          if (strlen($trimmedPart) > 200) {
            $session->addErrorMessage('Each perk must be 200 characters or less.');
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
            exit;
          }
          if (strip_tags($trimmedPart) !== $trimmedPart) {
            $session->addErrorMessage('Perks cannot contain HTML tags.');
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
            exit;
          }
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