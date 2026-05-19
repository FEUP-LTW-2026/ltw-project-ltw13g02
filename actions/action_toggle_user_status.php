<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');

  $session = new Session();

  if (empty($_POST['user_id']) || !isset($_POST['is_active'])) {
    $session->addMessage('error', 'Invalid request.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }

  $db = getDatabaseConnection();
  $userId = (int) $_POST['user_id'];
  $isActive = (int) $_POST['is_active'];

  try {
    $stmt = $db->prepare('UPDATE User SET is_active = ? WHERE id = ?');
    $stmt->execute([$isActive, $userId]);

    $session->addMessage('success', 'User status updated successfully.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  } catch (PDOException $e) {
    $session->addMessage('error', 'Database error occurred.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
?>