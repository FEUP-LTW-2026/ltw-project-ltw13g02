<?php

// This file is used to solve the problem of users that are logged in and their role was changed.
// As the session is still active, they won't see the changes until they log out 
// and log in again, which can be a problem if they can still perform actions
// that they shouldn't be able to perform with the new role.

declare(strict_types=1);

// If a Session instance is not already available, create one.
if (!isset($session)) {
  require_once (__DIR__ . '/../utils/session.php');
  $session = new Session();
}

if (!$session->isLoggedIn()) {
  header('Location: ../pages/login.php');
  exit;
}

require_once __DIR__ . '/../database/connection.db.php';
$db = getDatabaseConnection();
require_once __DIR__ . '/../database/authenticated_user.class.php';

$roleChangeCount = AuthenticatedUser::getUserRoleChangeCount($db, (int) $session->getId());

if ($roleChangeCount !== $session->getRoleChangeCount()) {
  $session->addErrorMessage('Your role has been changed. Please log in again to see the changes.');
  $session->invalidateAuth(); // Unsets all values related to the session but allows the session to continue for messages
  header('Location: ../pages/login.php'); // Shows the login page again with the message
  exit;
}
?>