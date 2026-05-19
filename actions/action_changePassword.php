<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit;
    }

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');

    $db = getDatabaseConnection();
    $user = AuthenticatedUser::getUser($db, $session->getId());

    if (!AuthenticatedUser::verifyPassword($db, $user->getId(), $_POST['current-password'])) {
        $session->addMessage('error', 'Current password is incorrect.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if ($_POST['new-password'] !== $_POST['confirm-password']) {
        $session->addMessage('error', 'Passwords do not match.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    try {
        AuthenticatedUser::updatePassword($db, $user->getId(), $_POST['new-password']);
        $session->addMessage('success', 'Password updated successfully!');
        header('Location: ../pages/profile.php');
        
    } catch (PDOException $e) {
        $session->addMessage('error', 'Database error occured.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
?>