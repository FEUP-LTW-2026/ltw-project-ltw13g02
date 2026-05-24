<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');

  $session = new Session();
  require_once(__DIR__ . '/../utils/role_change_guard.php');

  if (!$session->isLoggedIn() || !$session->isAdmin()) {
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php'));
    exit;
  }

  if (empty($_POST['tier_id'])) {
    $session->addErrorMessage('Invalid request.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
    exit;
  }

  if (($_POST['csrf_token'] ?? '') !== $session->getCSRFToken()) {
    $session->addErrorMessage('Invalid CSRF token. Please try again.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
    exit;
  }

  $db = getDatabaseConnection();
  $tierId = (int)$_POST['tier_id'];
  try {
    $db->beginTransaction();

    $stmt = $db->prepare('SELECT is_active FROM MembershipTier WHERE id = ?');
    $stmt->execute([$tierId]);
    $tier = $stmt->fetch();

    if (!$tier) {
      $db->rollBack();
      $session->addErrorMessage('Membership tier not found.');
      header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
      exit;
    }

    if ((bool)$tier['is_active']) {
      $db->rollBack();
      $session->addErrorMessage('Active membership tiers cannot be deleted. Disable the tier first.');
      header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
      exit;
    }

    // Checks if any memberships are still referencing this tier
    $stmt = $db->prepare('SELECT COUNT(*) FROM Membership WHERE membership_tier_id = ?');
    $stmt->execute([$tierId]);
    $membershipCount = (int)$stmt->fetchColumn();

    if ($membershipCount > 0) {
      $db->rollBack();
      $session->addErrorMessage('This membership tier cannot be deleted because it is still referenced by memberships.');
      header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
      exit;
    }

    $stmt = $db->prepare('DELETE FROM MembershipTier WHERE id = ?');
    $stmt->execute([$tierId]);

    $db->commit();

    $session->addSuccessMessage('Membership tier deleted successfully.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
    exit;
  } catch (PDOException $e) {
    if ($db->inTransaction()) $db->rollBack();
    $session->addErrorMessage('Database error occurred while deleting the tier.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
    exit;
  }
?>