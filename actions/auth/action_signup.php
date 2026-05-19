<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../../utils/session.php');

    $session = new Session();
    
    // Verify if the form fields are empty
    if (empty($_POST['first-name']) || empty($_POST['last-name']) || empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['confirm-password'])) {
        $session->addErrorMessage('All fields are mandatory.');
        header('Location: ' . $_SERVER['HTTP_REFERER']); 
        exit;
    }

    // Verify if the "Password" and "Confirm Password" fields match
    if ($_POST['password'] !== $_POST['confirm-password']) {
        $session->addErrorMessage('Passwords do not match.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    require_once(__DIR__ . '/../../database/connection.db.php');
    require_once(__DIR__ . '/../../database/authenticated_user.class.php');

    $db = getDatabaseConnection();

    if (!AuthenticatedUser::isEmailAvailable($db, $_POST['email'])) {
        $session->addErrorMessage('That email is already in use.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (!AuthenticatedUser::isUsernameAvailable($db, $_POST['username'])) {
        $session->addErrorMessage('That username is already in use.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    require_once(__DIR__ . '/../../database/authenticated_user.class.php');

    try {
        $user = AuthenticatedUser::createUser($db, $_POST['first-name'], $_POST['last-name'], $_POST['email'], $_POST['username'], $_POST['password']);

        $session->setId($user->getId());
        $session->setUsername($user->getUsername());
        $session->setEmail($user->getEmail());
        $session->addSuccessMessage('Successfully signed up!');
        header('Location: /../../pages/index.php');
        exit;
        
    } catch (PDOException $e) {
        $session->addErrorMessage('Database error occured.');
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>