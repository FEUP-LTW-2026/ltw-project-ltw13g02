<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../../utils/session.php');

    $session = new Session();

    $firstName = $_POST['first-name'] ?? '';
    $lastName = $_POST['last-name'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';

    // Verify if the form fields are empty
    if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
        $session->addErrorMessage('All fields are mandatory.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (!preg_match('/^[a-zA-Z]+$/', $firstName)) {
        $session->addErrorMessage('First name can only contain letters.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (!preg_match('/^[a-zA-Z]+$/', $lastName)) {
        $session->addErrorMessage('Last name can only contain letters.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // TODO: check if this is valid.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $session->addErrorMessage('Invalid email address.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $session->addErrorMessage('Username can only contain letters, numbers and underscores.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // TODO: check it there's need for stronger password requirements.
    if (strlen($password) < 8) {
        $session->addErrorMessage('Password must be at least 8 characters.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if ($password !== $confirmPassword) {
        $session->addErrorMessage('Passwords do not match.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    require_once(__DIR__ . '/../../database/connection.db.php');
    require_once(__DIR__ . '/../../database/authenticated_user.class.php');

    $db = getDatabaseConnection();

    if (!AuthenticatedUser::isEmailAvailable($db, $email)) {
        $session->addErrorMessage('That email is already in use.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (!AuthenticatedUser::isUsernameAvailable($db, $username)) {
        $session->addErrorMessage('That username is already in use.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    try {
        $user = AuthenticatedUser::createUser($db, $firstName, $lastName, $email, $username, $password);

        $session->generateCSRFToken();
        $session->setId($user->getId());
        $session->setUsername($user->getUsername());
        $session->setEmail($user->getEmail());
        $session->addSuccessMessage('Successfully signed up!');
        header('Location: /pages/index.php');
        exit;

    } catch (PDOException $e) {
        $session->addErrorMessage('Database error occurred.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
?>