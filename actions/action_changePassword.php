<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../utils/helpers.php');
    
    $session = new Session();
    
    require_once(__DIR__ . '/../utils/protected_file_guard.php');
    
    if (!$session->isLoggedIn()) {
        redirectToLogin();
    }

    if(empty($_POST['current-password']) || empty($_POST['new-password']) || empty($_POST['confirm-password'])) {
        $session->addErrorMessage('All password fields are required.');
        redirectToPreviousPage();
    }

    if(!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token.');
        redirectToPreviousPage();
    }

    if (!validatePassword($_POST['new-password'])) {
        $session->addErrorMessage('Password must be at least 8 characters.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();
    $user = AuthenticatedUser::getUser($db, $session->getId());

    if (!AuthenticatedUser::verifyPassword($db, $user->getId(), $_POST['current-password'])) {
        $session->addErrorMessage('Current password is incorrect.');
        redirectToPreviousPage();
    }

    if ($_POST['new-password'] !== $_POST['confirm-password']) {
        $session->addErrorMessage('Passwords do not match.');
        redirectToPreviousPage();
    }

    try {
        AuthenticatedUser::updatePassword($db, $user->getId(), $_POST['new-password']);
        $session->addSuccessMessage('Password updated successfully!');
        header('Location: ../pages/profile.php');
        exit;
    } catch (PDOException $e) {
        $session->addErrorMessage('Database error occured.');
        redirectToPreviousPage();
    }
?>