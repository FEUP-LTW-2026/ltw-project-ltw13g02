<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if(!$session->isLoggedIn()) {
    header('Location: ../pages/login.php');
    exit;
  }

  if(!isset($_POST['tier_id'], $_POST['duration'], $_POST['payment_type'])) {
    $session->addErrorMessage('All fields are required.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }

  require_once (__DIR__ . '/../database/connection.db.php');
  $db = getDatabaseConnection();

  require_once (__DIR__ . '/../database/membership_tier.class.php');

  $tier_id = (int) $_POST['tier_id'];
  $duration = (int) $_POST['duration'];
  $payment_type = $_POST['payment_type'];

  try {
    $tier = MembershipTier::getMembershipTierById($db, $tier_id);
  } catch (Exception $e) {
    $session->addErrorMessage('Selected membership tier does not exist.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }

  // First month insertion
  try {
    $db->beginTransaction();
    // Check if member exists: if not, create it (to satisfy foreign key constraint)
    $stmt = $db->prepare('SELECT id FROM Member WHERE id = ?');
    $stmt->execute([$session->getId()]);
    if ($stmt->fetch() === false) {
      $stmt = $db->prepare('INSERT INTO Member (id) VALUES (?)');
      $stmt->execute([$session->getId()]);
    }
    
    // Create first month
    $stmt = $db->prepare("
      INSERT INTO Membership (member_id, start_timestamp, end_timestamp, status, 
      membership_tier_id, payment_due_timestamp, payment_amount, payment_status) 
      VALUES (?, datetime('now', 'localtime'), datetime('now', '+1 month', 'localtime'), ?, ?, datetime('now', 'localtime'), ?, ?)");
    $stmt->execute([$session->getId(), 'active', $tier_id, $tier->getMonthlyFee(), 'paid']);
    
    $db->commit();
  } catch (Exception $e) {
    $db->rollBack();
    $session->addErrorMessage($e->getMessage());
    $session->addErrorMessage('Error creating first month of membership.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
  
  // Future months insertion
  if($duration > 1) {
    try {
      $db->beginTransaction();
      for ($i = 1; $i < $duration; $i++) {
        $month_offset = "+" . $i . " months";
        $next_month_offset = "+" . ($i + 1) . " months";
        
        if ($payment_type === 'monthly') {
          $stmt = $db->prepare("
            INSERT INTO Membership (member_id, start_timestamp, end_timestamp, status, membership_tier_id,
            payment_due_timestamp, payment_amount, payment_status) 
            VALUES (?, datetime('now', ?, 'localtime'), datetime('now', ?, 'localtime'), ?, ?, datetime('now', ?, 'localtime'), ?, ?)");
          $stmt->execute([$session->getId(), $month_offset, $next_month_offset, 'pending', $tier_id, $month_offset, $tier->getMonthlyFee(), 'pending']);
        
        } else if ($payment_type === 'upfront') {
          $stmt = $db->prepare("
            INSERT INTO Membership (member_id, start_timestamp, end_timestamp, status, membership_tier_id, 
            payment_due_timestamp, payment_amount, payment_status) 
            VALUES (?, datetime('now', ?, 'localtime'), datetime('now', ?, 'localtime'), ?, ?, datetime('now', 'localtime'), ?, ?)");
          $stmt->execute([$session->getId(), $month_offset, $next_month_offset, 'planned', $tier_id, $tier->getMonthlyFee(), 'paid']);
        }
      }
      $db->commit();
    } catch (Exception $e) {
      if ($db->inTransaction()) $db->rollBack();
      $session->addErrorMessage('Error creating future months of membership.');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }
  $session->addSuccessMessage('Membership created successfully!');
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit;
?>