<?php 
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/role_change_guard.php');

    if(!$session->isLoggedIn() || !$session->isAdmin()) {
        redirectToLogin();
    }

    if (empty($_POST['tier_id']) || empty($_POST['name']) || empty($_POST['price']) || empty($_POST['booking_window_days'])) {
        $session->addErrorMessage('Invalid request. Please fill in all required fields.');
        redirectToPreviousPage();
    }

    validateCSRFToken($_POST['csrf_token'], $session);

    $tierId = (int) $_POST['tier_id'];
    if($tierId <= 0) {
        $session->addErrorMessage('Invalid membership tier ID.');
        redirectToPreviousPage();
    }

    $name = trim($_POST['name']);
    if(!validateText($name)) {
        $session->addErrorMessage('Invalid name. Please use only letters and spaces.');
        redirectToPreviousPage();
    }

    $price = (float) $_POST['price'];
    if($price < 0) {
        $session->addErrorMessage('Invalid price. Please enter a non-negative value.');
        redirectToPreviousPage();
    }

    $bookingWindowDays = (int) $_POST['booking_window_days'];
    if($bookingWindowDays < 0) {
        $session->addErrorMessage('Invalid booking window. Please enter a non-negative integer.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    if (isset($_POST['description']) && is_array($_POST['description'])) {
      // Artificially add semicolons
      for($i = 0; $i < count($_POST['description']); $i++) {
        // Only add semicolon if it's not the last element
        $_POST['description'][$i] = trim($_POST['description'][$i]) . ($i < count($_POST['description']) - 1 ? ';' : '');
      }
      $description = implode('', $_POST['description']);
    }

    try {
        $stmt = $db->prepare('UPDATE MembershipTier SET name = ?, description = ?, monthly_fee = ?, booking_window_days = ? WHERE id = ?');
        $stmt->execute([$name, $description, $price, $bookingWindowDays, $tierId]);

        $session->addSuccessMessage('Membership tier updated successfully.');
    } catch (PDOException $e) {
        $session->addErrorMessage('Database error occurred while updating the tier.');
    }

    redirectToPreviousPage();
?>