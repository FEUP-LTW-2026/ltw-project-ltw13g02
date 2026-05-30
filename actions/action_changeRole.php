<?php 
    declare(strict_types=1);

    require_once (__DIR__ . '/../database/connection.db.php');
    require_once (__DIR__ . '/../database/authenticated_user.class.php');
    require_once (__DIR__ . '/../database/roleManager.class.php');
    require_once (__DIR__ . '/../utils/session.php');
    require_once (__DIR__ . '/../utils/helpers.php');
    
    $session = new Session();
    
    require_once (__DIR__ . '/../utils/protected_file_guard.php');

    if (!$session->isLoggedIn()) {
        redirectToLogin();
    }

    if (!$session->isAdmin()) {
        header('Location: index.php');
        exit;
    }

    $required = ['user_id', 'user_name', 'old_role_table', 'new_role_table', 'csrf_token'];

    if (!validateRequiredFields($required)) {
        $session->addErrorMessage('All fields are required.');
        redirectToPreviousPage();
    }

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }

    $userId = (int) $_POST['user_id'];
    if($userId <= 0) {
        $session->addErrorMessage('Invalid user ID.');
        redirectToPreviousPage();
    }

    $username = trim((string) $_POST['user_name']);
    if(!validateUsername($username)) {
        $session->addErrorMessage('Invalid username.');
        redirectToPreviousPage();
    }

    $oldRole = $_POST['old_role_table'];
    $newRole = $_POST['new_role_table'];
    if(!validateText($oldRole) || !validateText($newRole)) {
        $session->addErrorMessage('Invalid role values.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    RoleManager::changeUserRole($db, $userId, $oldRole, $newRole, $session);
?>